package dance.client {

import flash.events.Event;
import flash.net.URLLoader;
import flash.net.URLRequest;
import flash.net.URLVariables;
import flash.net.navigateToURL;
import flash.utils.clearInterval;
import flash.utils.getTimer;
import flash.utils.setInterval;

import com.threerings.util.Controller;
import com.threerings.util.MethodQueue;
import com.threerings.util.ValueEvent;
import com.threerings.util.StringUtil;

import com.whirled.ControlEvent;
import com.whirled.EntityControl;
import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.net.REMOTE;
import aduros.net.RemoteCaller;
import aduros.net.RemoteProvider;
import aduros.util.F;

import dance.DanceTools;
import dance.data.Codes;
import dance.data.Note;
import dance.data.ScoreSummary;
import dance.data.Song;
import dance.data.Track;

public class DanceController extends Controller
{
    // Controller commands
    public static const QUIT :String = "Quit";
    public static const LOCATE_PEERS :String = "LocatePeers";
    public static const INVITE :String = "Invite";
    public static const UPDATE_DIFFICULTY :String = "UpdateDifficulty";
    public static const SHOW_PROFILE :String = "ShowProfile";
    public static const STOP_DANCING :String = "StopDancing";
    public static const SWITCH_STATE :String = "SwitchState";
    public static const ADD_MUSIC :String = "AddMusic";
    public static const BUY_MUSIC :String = "BuyMusic";

    public static const SHARE_TWITTER :String = "ShareTwitter";
    public static const SHARE_FACEBOOK :String = "ShareFacebook";
    public static const SHARE_WHIRLED :String = "ShareWhirled";

    public static const STEP_ON :String = "StepOn";
    public static const STEP_OFF :String = "StepOff";

    // Pads corresponding to the 4 keys
    public static const PAD_LEFT :int = 0;
    public static const PAD_DOWN :int = 1;
    public static const PAD_UP :int = 2;
    public static const PAD_RIGHT :int = 3;

    // Scoring constants
    public static const TAP_SCORE :int = 75; // per tier
    public static const HOLD_SCORE :int = 200;
    public static const BOO_SCORE :int = -150;

    public static const REMINDER_XP_MILESTONE :int = 3800; // Level 18-19

    public var view :DanceView;

    public function DanceController ()
    {
        _roomService = new RemoteCaller(Game.ctrl.agent, Codes.MSG_SERVICE_ROOM);
        _gameService = new RemoteCaller(Game.ctrl.agent, Codes.MSG_SERVICE_GAME);
        new RemoteProvider(Game.ctrl.player, Codes.MSG_SERVICE_PLAYER, F.konst(this));
        new RemoteProvider(Game.ctrl.game, Codes.MSG_RECEIVER_GAME, F.konst(this));

        _model = new DanceModel();

        _model.addEventListener(NoteHitEvent.NOTE_HIT, onNoteHit);
        _model.addEventListener(HoldEvent.HOLD_COMPLETE, onHoldComplete);
        _model.addEventListener(NoteEvent.NOTE_MISSED, onGeneralBooch);
        _model.addEventListener(HoldEvent.HOLD_MISSED, onGeneralBooch);
        _model.addEventListener(HoldEvent.HOLD_FAILED, onGeneralBooch);
        _model.addEventListener(DanceModel.BOO, onBoo);
        _model.addEventListener(DanceModel.LIFE_UPDATED, onLifeUpdated);

        _model.addEventListener(DanceModel.DANCE_STARTED, onDanceStarted);
        _model.addEventListener(DanceModel.DANCE_ENDED, onDanceEnded);

        view = new DanceView(_model);
        view.setState(DanceView.STATE_WAITING);
        setControlledPanel(view);

        _songFactory = new SongFactory();
        _songFactory.addEventListener(SongFactory.SONG_LOADED, onSongLoaded);
        _songFactory.addEventListener(SongFactory.SONG_ERROR, onSongError);

        _watcher = new DanceWatcher(_model);

        Game.ctrl.room.addEventListener(ControlEvent.MUSIC_ID3, onMusicId3);
        Game.ctrl.room.addEventListener(ControlEvent.MUSIC_STARTED, onMusicStarted);
        Game.ctrl.room.addEventListener(ControlEvent.MUSIC_STOPPED, onMusicStopped);

        Game.ctrl.room.addEventListener(AVRGameRoomEvent.AVATAR_CHANGED, function (event :AVRGameRoomEvent) :void {
            if (event.value == Game.ctrl.player.getPlayerId()) {
                MethodQueue.callLater(checkAvatarTrophy);
            }
        });
        checkAvatarTrophy();

        Game.ctrl.room.addEventListener(ControlEvent.CHAT_RECEIVED, onChat);

        Game.ctrl.player.addEventListener(AVRGamePlayerEvent.LEFT_ROOM, onLeftRoom);
        Game.ctrl.player.addEventListener(AVRGamePlayerEvent.ENTERED_ROOM, F.justOnce(onFirstRoom));
        Game.ctrl.player.addEventListener(AVRGamePlayerEvent.TASK_COMPLETED, onTaskCompleted);
        Game.ctrl.player.props.addEventListener(PropertyChangedEvent.PROPERTY_CHANGED, onPlayerPropertyChanged);

        updateAvatarLevel();
    }

    protected function checkAvatarTrophy () :void
    {
        var avatarId :int = Game.ctrl.player.getAvatarMasterItemId();
        if ((avatarId == 173223 || avatarId == 173226) && !Game.ctrl.player.holdsTrophy(Codes.TROPHY_AVATAR_BUYER)) {
            _gameService.apply("awardAvatarBuyerTrophy");
        }
    }

    protected function onPlayerPropertyChanged (event :PropertyChangedEvent) :void
    {
        if (event.name == "@xp") {
            var oldXp :Number = Number(event.oldValue);
            var newXp :Number = Number(event.newValue);
            if (newXp > oldXp) {
                Game.ctrl.local.feedback(Messages.en.xlate("m_xpEarned", Math.floor(newXp-oldXp)));
                var newLevel :int = DanceTools.xpToLevel(newXp);
                if (newLevel > DanceTools.xpToLevel(oldXp)) {
                    if (newLevel in LevelDisplay.MILESTONES) {
                        Game.ctrl.local.feedback(Messages.en.xlate("m_levelUpMilestone", newLevel,
                            Messages.en.xlate(LevelDisplay.MILESTONES[newLevel])));
                    } else {
                        Game.ctrl.local.feedback(Messages.en.xlate("m_levelUp", newLevel));
                    }
                    Game.metrics.trackEvent("Level ups", String(newLevel));
                }

                if (oldXp < REMINDER_XP_MILESTONE && newXp > REMINDER_XP_MILESTONE) {
                    Game.ctrl.local.feedback(Messages.en.xlate("m_rateReminder"));
                }
            }
            updateAvatarLevel();
        }
    }

    protected function onTaskCompleted (event :AVRGamePlayerEvent) :void
    {
        var coins :int = event.value as int;
        switch (event.name) {
            case Codes.TASK_LOYALTY:
                Game.ctrl.local.feedback(Messages.en.xlate("m_loyalty", coins));
                break;
            case Codes.TASK_DJ:
                Game.ctrl.local.feedback(Messages.en.xlate("m_djPayout", coins));
                _gameService.apply("hackWonDJCoins", coins ^ 0x48ab2c);
                break;
        }
    }

    protected function updateAvatarLevel () :void
    {
        for each (var entityId :String in Game.ctrl.room.getEntityIds(EntityControl.TYPE_AVATAR)) {
            if (Game.ctrl.room.getEntityProperty(EntityControl.PROP_MEMBER_ID, entityId) == Game.ctrl.player.getPlayerId()) {
                var setLevel :Function = Game.ctrl.room.getEntityProperty("ddr:setLevel", entityId) as Function;
                if (setLevel != null) {
                    setLevel(Game.level);
                }
                return;
            }
        }
    }

    protected function onChat (event :ControlEvent) :void
    {
        var playerId :int = Game.ctrl.player.getPlayerId();
        if (Codes.isAdmin(playerId) &&
            Game.ctrl.room.getEntityProperty(EntityControl.PROP_MEMBER_ID, event.name) == playerId) {

            var command :Array = event.value.match(/^!(\w*)\s+(.*)/);
            if (command != null) {
                switch (command[1]) {
                    case "broadcast":
                        _gameService.apply("requestBroadcast", command[2]);
                        break;

                    case "setlevel":
                        var args :Array = command[2].split(" ");
                        var level :int = args[0];
                        var who :int = (args.length > 1) ? args[1] : playerId;
                        _gameService.apply("requestLevelHack", who, level);
                        break;

                    default:
                        Game.ctrl.local.feedback("Not a command: " + command[1]);
                        break;
                }
            }
        }
    }

    protected function onFirstRoom (event :AVRGamePlayerEvent) :void
    {
        // Track the first room view for admins, that's it
        Game.metrics.enabled = !Codes.isAdmin(Game.ctrl.player.getPlayerId());

        var loader :URLLoader = new URLLoader();
        loader.addEventListener(Event.COMPLETE, function (event :Event) :void {
            var response :XML = XML(loader.data);
            Game.log.info("Received geocoding response", "status", response.Status);
            if (response != null && response.Status == "OK") {
                Game.log.info("Got location", "country", response.CountryCode);
                Game.ctrl.player.props.set(Codes.PROP_COUNTRY, String(response.CountryCode).toUpperCase());
            }
        });
        loader.load(new URLRequest("http://ipinfodb.com/ip_query_country.php"));

        var djPayout :Number = Game.ctrl.player.props.get(Codes.PROP_DJ_PAYOUT) as Number;
        if (djPayout > 0) {
            Game.ctrl.local.feedback(Messages.en.xlate("m_clubsCollected"));
            if (djPayout > Codes.MAX_DJ_PAYOUT) {
                Game.ctrl.local.feedback(Messages.en.xlate("m_clubsLimitted", Codes.MAX_DJ_PAYOUT));
            }
        }

        _gameService.apply("clientReady");
    }

    public function handleQuit () :void
    {
        Game.ctrl.local.feedback(Messages.en.xlate("m_bye"));
        Game.ctrl.player.deactivateGame();
        Game.metrics.trackEvent("Buttons", "Quit");
    }

    public function handleLocatePeers () :void
    {
        _gameService.apply("locatePeers");
        Game.metrics.trackEvent("Buttons", "LocatePeers");
    }

    public function handleUpdateDifficulty (difficulty :int) :void
    {
        Game.ctrl.player.props.set(Codes.PROP_DIFFICULTY, difficulty, true);
    }

    public function handleShowProfile (playerId :int) :void
    {
        Game.ctrl.local.showPage("people-" + playerId);
        Game.metrics.trackEvent("Buttons", "ShowProfile");
    }

    public function handleSwitchState (state :int) :void
    {
        view.setState(state);
    }

    public function handleAddMusic () :void
    {
        Game.ctrl.local.showPage("stuff-7_" +
            Game.ctrl.player.getPlayerId() + "_0_" + Codes.SONG_KEYWORD);
        Game.metrics.trackEvent("Buttons", "AddMusic");
    }

    public function handleBuyMusic () :void
    {
        Game.ctrl.local.showPage("shop-7_7_s" + Codes.SONG_KEYWORD);
        Game.metrics.trackEvent("Buttons", "BuyMusic");
    }

    public function handleInvite () :void
    {
        Game.ctrl.local.showInvitePage(Messages.en.xlate("m_invite"));
        Game.metrics.trackEvent("Buttons", "Invite");
    }

    public function handleShareTwitter (status :String) :void
    {
        var req :URLRequest = new URLRequest("http://twitter.com/home/");
        req.data = new URLVariables();
        req.data.status = status;
        navigateToURL(req);
        Game.metrics.trackEvent("Share", "Twitter");
    }

    public function handleShareFacebook (status :String) :void
    {
        var req :URLRequest = new URLRequest("http://www.facebook.com/sharer.php");
        req.data = new URLVariables();
        req.data.u = "http://www.whirled.com/friend/" + Game.ctrl.player.getPlayerId() + "/world-game_p_" + Codes.GAME_ID;
        req.data.t = status; // Not working?
        navigateToURL(req);
        Game.metrics.trackEvent("Share", "Facebook");
    }

    public function handleShareWhirled (status :String) :void
    {
        Game.ctrl.local.showInvitePage(status);
        Game.metrics.trackEvent("Share", "Whirled");
    }

    public function handleStopDancing () :void
    {
        _model.stop(Codes.STOP_SURRENDER);
    }

    public function handleStepOn (pad :int) :void
    {
        _model.stepOn(pad);
    }

    public function handleStepOff (pad :int) :void
    {
        _model.stepOff(pad);
    }

    REMOTE function didBroadcast (name :String, message :String) :void
    {
        Game.ctrl.local.feedback(Messages.en.xlate("m_broadcast", name, message));
    }

    REMOTE function peersLocated (rooms :Array) :void
    {
        Game.ctrl.local.feedback(Messages.en.xlate("m_locatedHeader"));
        trace(rooms.length);
        trace(rooms);
        for each (var room :Object in rooms) {
            Game.ctrl.local.feedback(Messages.en.xlate("m_locatedRoom", room.roomId, room.name, room.pop));
        }
    }

    protected function onNoteHit (event :NoteHitEvent) :void
    {
        _model.combo += 1;

        if (_model.life > 0) {
            _model.score += (event.tier+1)*TAP_SCORE * _model.multiplier;
            _model.life += 0.1;
        }
    }

    protected function onHoldComplete (event :Event) :void
    {
        _model.combo += 1;

        if (_model.life > 0) {
            _model.score += HOLD_SCORE * _model.multiplier;
            _model.life += 0.1;
        }
    }

    protected function onGeneralBooch (event :Event) :void
    {
        _model.combo = 0;
        _model.life -= 0.05;
    }

    protected function onBoo (event :Event) :void
    {
        if (_model.life > 0) {
            _model.score += BOO_SCORE;
            _model.life -= 0.025;
        }
    }

    protected function onLifeUpdated (event :Event) :void
    {
        if (_model.life == 0) {
            //_model.score /= 2;
            Game.setAvatarState("Default", "Sitting", "Sit");
        }
    }

    protected function onSongLoaded (event :ValueEvent) :void
    {
        var song :Song = Song(event.value);

        Game.ctrl.local.feedback(Messages.en.xlate(
            song.credit != "T" ? "m_started" : "m_startedNoCredit", // Yeah, I don't fucking know. Python converts null/empty string to T?
            Game.getDJName(Game.ctrl.room.getMusicOwnerId()),
            song.title, song.artist, song.credit));

        var difficulty :int = Game.ctrl.player.props.get(Codes.PROP_DIFFICULTY) as int;
        if (difficulty in song.tracks) {
            // Ready
        } else {
            var originalDifficulty :int = difficulty;
            for (; difficulty >= 0; --difficulty) {
                if (difficulty in song.tracks) {
                    break;
                }
            }
            if (difficulty < 0) {
                for (; difficulty < Codes.DIFFICULTIES; ++difficulty) {
                    if (difficulty in song.tracks) {
                        break;
                    }
                }
                if (difficulty == Codes.DIFFICULTIES) {
                    Game.metrics.trackEvent("Errors", "e_no_difficulties,"+song.url);
                    throw new Error("Broken song. No difficulty levels?");
                }
            }
            Game.ctrl.local.feedback(Messages.en.xlate("m_noDifficulty",
                Messages.en.xlate("l_difficulty"+originalDifficulty),
                Messages.en.xlate("l_difficulty"+difficulty),
                song.title));
        }

        _model.difficulty = difficulty;

        _model.play(song);
    }

    protected function onSongError (event :ValueEvent) :void
    {
        Game.log.error("Couldn't load song", "cause", event.value);
        Game.ctrl.local.feedback(Messages.en.xlate(event.value));
        Game.metrics.trackEvent("Errors", String(event.value));
    }

    protected function onMusicId3 (event :ControlEvent) :void
    {
        if (_model.song == null) {
            var id3 :Object = Game.ctrl.room.getMusicId3();

            // Yes, I've seen this be null before
            if (id3 != null && id3.comment != null) {
                Game.log.info("Got commented MP3", "comment", id3.comment);

                var match :Array = id3.comment.match(/ddr=(.*)/);
                if (match != null) {
                    var ident :String = match[1];
                    _songFactory.load(ident);
                }
            }
        }
    }

    protected function onMusicStarted (event :ControlEvent) :void
    {
        _model.startedOn = getTimer();
        // We'll actually play() if the ID3 comes in with a valid song
    }

    protected function onMusicStopped (event :ControlEvent) :void
    {
        _model.stop();
    }

    protected function onLeftRoom (event :AVRGamePlayerEvent) :void
    {
        _model.stop();
    }

    protected function onDanceStarted (event :Event) :void
    {
        _updateInterval = setInterval(sendUpdate, 2000);

        _roomService.apply("didStartDance", _watcher.createOptions().toBytes());

        view.setState(DanceView.STATE_PLAYING);

        Game.setAvatarState("Dancing", "Dance");

        for each (var entityId :String in Game.ctrl.room.getEntityIds()) {
            var callback :Function =
                Game.ctrl.room.getEntityProperty("ddr:started", entityId) as Function;

            if (callback != null) {
                try {
                    callback({
                        title: _model.song.title,
                        artist: _model.song.artist,
                        credit: _model.song.credit,
                        bpm: _model.song.bpm,
                        difficulty: _model.difficulty
                    });
                } catch (error :*) {
                    Game.log.warning("Couldn't pass song info to entity", "error", error);
                }
            }
        }

        Game.metrics.trackEvent("Songs", _model.song.url, _model.song.title);
        Game.metrics.trackEvent("Stats", "difficulty", null, String(_model.difficulty));
    }

    protected function onDanceEnded (event :ValueEvent) :void
    {
        // TODO: Have the model fire a DanceEnded event when the game is unloaded? -- It does already?
        clearInterval(_updateInterval);

        Game.ctrl.doBatch(function () :void {
            var status :int = event.value as int;
            if (status != Codes.STOP_INTERRUPT) {
                var summary :ScoreSummary = _watcher.createSummary();
                _roomService.apply("didStopDance", _model.song.url, status, summary.toBytes());
                view.setState(DanceView.STATE_SCORES);

                Game.metrics.trackEvent("Stats", "rating", null, summary.rating);
                Game.metrics.trackEvent("Stats", "accuracy", null, summary.accuracy);
                Game.metrics.trackEvent("Stats", "bestCombo", null, summary.bestCombo);
            } else {
                view.setState(DanceView.STATE_WAITING);
            }

            Game.setAvatarState("Standing", "Default");
        });

        for each (var entityId :String in Game.ctrl.room.getEntityIds()) {
            Game.ctrl.room.getEntityProperty("ddr:ended", entityId); // Poke the entity
        }
    }

    protected function sendUpdate () :void
    {
        _roomService.apply("update", _watcher.createCard().toBytes());
    }

    protected var _model :DanceModel;

    protected var _songFactory :SongFactory;
    protected var _watcher :DanceWatcher;

    protected var _updateInterval :uint;

    protected var _roomService :RemoteCaller;
    protected var _gameService :RemoteCaller;
}

}
