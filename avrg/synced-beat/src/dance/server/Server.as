﻿package dance.server {

import flash.utils.ByteArray;
import flash.utils.Dictionary;

import com.threerings.util.Log;

import com.whirled.ServerObject;
import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.game.Scheduler;
import aduros.game.StatTracker;
import aduros.game.Job;
import aduros.i18n.MessageUtil;
import aduros.net.REMOTE;
import aduros.net.RemoteCaller;
import aduros.net.RemoteProvider;
import aduros.util.F;

import dance.DanceTools;
import dance.data.Codes;
import dance.data.DanceOptions;

public class Server extends ServerObject
{
    public static var ctrl :AVRServerGameControl;
    public static const log :Log = Log.getLog(Server);

    public function Server ()
    {
        trace("Hi Zell");
        log.info("Dance " + BuildConfig.WHEN + ". If you start me up...", "debug", BuildConfig.DEBUG);

        ctrl = new AVRServerGameControl(this);
        ctrl.game.addEventListener(AVRGameControlEvent.PLAYER_JOINED_GAME, onPlayerJoin);
        ctrl.game.addEventListener(AVRGameControlEvent.PLAYER_QUIT_GAME, onPlayerQuit);

        new RemoteProvider(ctrl.game, Codes.MSG_SERVICE_GAME, F.konst(this));
        new RemoteProvider(ctrl.game, Codes.MSG_SERVICE_ROOM, function (senderId :int) :Object {
            return getPlayer(senderId).room;
        });

        _gameReceiver = new RemoteCaller(ctrl.game, Codes.MSG_RECEIVER_GAME);

//        new Scheduler(ctrl.props, [
//            new Job(daily, 0, 0), // Midnight
//            new Job(monthly, 0, 0, 1), // Last midnight of each month
//        ]);

//        // Reset scoreboard
//        ctrl.props.set(ServerCodes.PROP_HIGHSCORE_DAILY, null, true);
//        ctrl.props.set(ServerCodes.PROP_HIGHSCORE_MONTHLY, null, true);
    }

    protected function requireAdmin (playerId :int) :void
    {
        if (!Codes.isAdmin(playerId)) {
            throw new Error("Admin required");
        }
    }

    REMOTE function requestBroadcast (playerId :int, message :String) :void
    {
        requireAdmin(playerId);

        var name :String = getPlayer(playerId).name;
        _gameReceiver.apply("didBroadcast", name, message);
    }

    REMOTE function requestLevelHack (playerId :int, targetId :int, level :int) :void
    {
        requireAdmin(playerId);

        var xp :Number = DanceTools.levelToXp(level);
        if (playerId in _players) {
            var player :Player = _players[targetId];
            player.ctrl.props.set("@xp", xp);
        } else {
            ctrl.loadOfflinePlayer(targetId, function (props :PropertySubControl) :void {
                props.set("@xp", xp);
            }, F.partial(log.warning, "Couldn't load offline player to level hack", "cause"));
        }
    }

    REMOTE function locatePeers (playerId :int) :void
    {
        var rooms :Array = []; // of loose Object
        for each (var room :RoomManager in _rooms) {
            var pop :int = room.ctrl.getPlayerIds().length; // room.players.length no worky
            if (pop > 0) {
                rooms.push({
                    roomId: room.ctrl.getRoomId(),
                    name: room.ctrl.getRoomName(),
                    pop: pop
                });
            }
        }

        var topN :Array = rooms.sortOn("pop", Array.NUMERIC | Array.DESCENDING).splice(0, 7);

        // Respond to client request
        getPlayer(playerId).playerReceiver.apply("peersLocated", topN);
    }

    /** Because the client can't award trophies, wheeee. */
    REMOTE function awardAvatarBuyerTrophy (playerId :int) :void
    {
        getPlayer(playerId).ctrl.awardTrophy(Codes.TROPHY_AVATAR_BUYER);
    }

    /** Task completed event not working on the server... sigh. */
    REMOTE function hackWonDJCoins (playerId :int, coins :int) :void
    {
        getPlayer(playerId).stats.submit("djCoins", coins ^ 0x48ab2c);
    }

    /** The client has connected and notified us that it's loaded and ready. */
    REMOTE function clientReady (playerId :int) :void
    {
        var player :Player = getPlayer(playerId);

        player.ctrl.doBatch(function () :void {
            var isNewPlayer :Boolean = player.ctrl.props.get(Codes.PROP_LAST_LOGIN) == null;

            // If they haven't logged in today
            var lastLogin :Date = new Date(player.ctrl.props.get(Codes.PROP_LAST_LOGIN));
            var now :Date = new Date();
            if (lastLogin.dateUTC != now.dateUTC
                || lastLogin.monthUTC != now.monthUTC
                || lastLogin.fullYearUTC != now.fullYearUTC) {
                player.ctrl.completeTask(Codes.TASK_LOYALTY, 0.3+Math.random()/2);
                player.stats.submit("xp", DanceTools.getLoyaltyBonusXp(player.level));
                player.ctrl.props.set(Codes.PROP_LAST_LOGIN, now.time);
            }

            // TODO: Launchers currently bugged, remove when fixed
            if (player.ctrl.getRoomId() == 2547123) { // Group home
                player.ctrl.moveToRoom(getBestParlor());
            }

            if (isNewPlayer) {
                player.ctrl.awardPrize("avatarGuy");
                player.ctrl.awardPrize("avatarGal");
            }

            if (player.djPayout > 0) {
                DanceTools.multiPayout(player.ctrl, Codes.TASK_DJ, Math.min(Codes.MAX_DJ_PAYOUT, player.djPayout));
                player.djPayout = 0;
            }
        });
    }

    /** Copy-pasta'd from Scribble. */
    protected function getBestParlor () :int
    {
        var pop :Array = []; // roomId -> population
        for each (var parlorId :int in Codes.PARLORS) {
            var room :RoomManager = getRoom(parlorId);
            pop[parlorId] = (room != null) ? room.ctrl.getPlayerIds().length : 0;
        }

        // List of parlor roomIds that are below max capacity
        var belowCapacity :Array = Codes.PARLORS.filter(function (parlorId :int, ..._) :Boolean {
            return pop[parlorId] < 8;
        });

        if (belowCapacity.length > 0) {
            // The highest populated room that's below capacity
            return F.foldl(function (bestId :int, parlorId :int) :int {
                return (pop[parlorId] > pop[bestId]) ? parlorId : bestId;
            }, belowCapacity[0], belowCapacity);

        } else {
            // The lowest populated room, if all the rooms are full anyways
            return F.foldl(function (bestId :int, parlorId :int) :int {
                return (pop[parlorId] < pop[bestId]) ? parlorId : bestId;
            }, Codes.PARLORS[0], Codes.PARLORS);
        }
    }


    public static function getRoom (roomId :int) :RoomManager
    {
        return _rooms[roomId];
    }

    public static function getPlayer (playerId :int) :Player
    {
        return _players[playerId];
    }

    public static function getRooms () :Dictionary
    {
        return _rooms;
    }

    /** Submits a stat to an online or offline player. */
    public static function submitOffline (playerId :int, statName :String, value :Object) :void
    {
        var player :Player = getPlayer(playerId);
        if (player != null) {
            // He's online, do it normally
            player.stats.submit(statName, value);
        } else {
            ctrl.loadOfflinePlayer(playerId, function (props :PropertySubControl) :void {
                StatTracker.applySubmit(props, statName, Player.STATS[statName], value);
            }, F.partial(log.error, "Couldn't load offline player for stat submission", "cause"));
        }
    }

    public static function depositToDJ (playerId :int, payout :Number) :void
    {
        if (playerId in _players) {
            var player :Player = _players[playerId];
            player.ctrl.doBatch(F.callback(DanceTools.multiPayout, player.ctrl, Codes.TASK_DJ, payout));
        } else {
            ctrl.loadOfflinePlayer(playerId, function (props :PropertySubControl) :void {
                var bank :Number = props.get(Codes.PROP_DJ_PAYOUT) as Number;
                props.set(Codes.PROP_DJ_PAYOUT, bank+payout);
            }, F.partial(log.warning, "Couldn't load offline DJ", "cause"));
        }
    }

    protected function onPlayerJoin (event :AVRGameControlEvent) :void
    {
        var playerId :int = int(event.value);

        if (playerId in _players) {
            log.warning("Player was already registered", "playerId", playerId);
        }

        var player :Player = new Player(ctrl.getPlayer(playerId));

        player.ctrl.addEventListener(AVRGamePlayerEvent.ENTERED_ROOM, onEnteredRoom);
        player.ctrl.addEventListener(AVRGamePlayerEvent.LEFT_ROOM, onLeftRoom);

//        player.ctrl.props.set("@xp", 0);

        _players[playerId] = player;
    }

    protected function onPlayerQuit (event :AVRGameControlEvent) :void
    {
        var playerId :int = int(event.value);
        var player :Player = getPlayer(playerId);

        if (player != null) {
            delete _players[playerId];
        } else {
            log.warning("Trying to deregister missing player", "playerId", playerId);
        }
    }

    protected function onEnteredRoom (event :AVRGamePlayerEvent) :void
    {
        var playerId :int = event.playerId;
        var roomId :int = int(event.value);

        var player :Player = getPlayer(playerId);
        var room :RoomManager = getRoom(roomId);

        if (room == null) {
            room = new RoomManager(ctrl.getRoom(roomId));
            room.ctrl.addEventListener(AVRGameRoomEvent.ROOM_UNLOADED, onRoomUnloaded);
            _rooms[roomId] = room;
        }

        var firstRoom :Boolean = (player.room == null);

        player.room = room;
        if (playerId in room.players) {
            log.warning("Player was already in entered room?", "playerId", playerId, "roomId", roomId);
        }
        room.players[playerId] = player;

        // Stuff a fake options, just to broadcast the player's level
        var fakeOpts :DanceOptions = new DanceOptions();
        fakeOpts.level = player.level;
        room.ctrl.props.setIn(Codes.PROP_OPTIONS, playerId, fakeOpts.toBytes());
    }

    protected function onRoomUnloaded (event :AVRGameRoomEvent) :void
    {
        var roomId :int = event.roomId;

        if (roomId in _rooms) {
            delete _rooms[roomId];
        } else {
            log.warning("Tried to cleanly unload an unregistered room", "roomId", roomId);
        }
    }

    protected function onLeftRoom (event :AVRGamePlayerEvent) :void
    {
        var playerId :int = event.playerId;
        var roomId :int = int(event.value);

        var player :Player = getPlayer(playerId);
        var room :RoomManager = getRoom(roomId);

        if (room == null) {
            log.warning("Player tried to leave an unregistered room",
                "playerId", playerId, "roomId", roomId);
        } else if (!(playerId in room.players)) {
            log.warning("Player wasn't in left room?", "playerId", playerId, "roomId", roomId);
        } else {
            delete room.players[playerId];
        }
    }

    protected var _gameReceiver :RemoteCaller;

    protected static var _players :Dictionary = new Dictionary(); // playerId -> Player
    protected static var _rooms :Dictionary = new Dictionary(); // roomId -> RoomManager
}

}
