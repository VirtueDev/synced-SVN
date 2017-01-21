package dance.client {

import aduros.i18n.MessageBundle;

public class Messages
{
    public static const en :MessageBundle = new MessageBundle({
        l_musicValid: "Already dancing... next song will begin shortly!",
        l_musicInvalid: "Need compatible music! <a href='" + DanceView.link(DanceController.BUY_MUSIC) + "'>Buy some</a> from the shop",
        l_musicNone: "No music in this room. Is your sound on?",

        m_locatedHeader: "\u2192 Current Top 7 Clubs:",
        m_locatedRoom: "{2} players in {1}: http://www.syncedonline.com/#world-s{0}",

        l_unfocused: "C L I C K",
        l_getReady0: "Get ready!",
        l_getReady1: "Here we go!",
        l_getReady2: "Let's do it!",

        l_hudTitle: "{0}  (Lvl {1})",

        m_broadcast: "Synced Beat broadcast by {0}: {1}",

        m_welcome: "Welcome back to Synced Beat, let's get this party started! Join the community at http://www.syncedonline.com/#groups-d_13396",
        m_welcomeNewbie: "Welcome to Synced Beat! When the music changes, use the arrow keys to strike the arrows at the right time. Full instructions: http://www.syncedonline.com/#games-d_2819",
        m_welcomeUpdated: "Welcome back to Synced Beat. There have been changes since you last played! Check out http://www.syncedonline.com/#groups-f_13396",
        m_rateReminder: "\u2605 I hope you're loving Synced Beat. Please rate it kindly at http://www.syncedonline.com/#games-d_2819 when you get the chance! \u2605",

        m_clubsCollected: "\u2605 Your clubs collected revenue while you were out! \u2605",
        m_clubsLimitted: "You hit the limit of {0} payments per login, try to come back more often to claim your earnings.",
        m_loyalty: "You won {0} coins and free experience just for logging in today. How much will you get tomorrow?",
        m_djPayout: "You collected {0} coins from DJing.",

        m_xpEarned: "You gained {0} experience!",
        m_levelUp: "\u2605 You advanced to level {0}! \u2605",
        m_levelUpMilestone: "\u2605 You advanced to level {0} and unlocked: {1}! \u2605",

        l_nextUnlock: "Next unlock: {1} at level {0}",
        l_unlockDifficulty1: "Light Difficulty",
        l_unlockDifficulty2: "Standard Difficulty",
        l_unlockDifficulty3: "Heavy Difficulty",
        l_unlockDifficulty4: "GURU Difficulty",
        l_unlockTrophy0: "Trophy + Prize (Basic Launcher)",
        l_unlockTrophy1: "Trophy + Prize (Album Covers)",
        l_unlockTrophy2: "Trophy + Prize (Harlequin Mage, Wyvern)",
        l_unlockTrophy3: "Trophy + Prize (Upgraded Toolbox, Scribble)",
        l_unlockTrophy4: "Trophy + Prize (Flipbook Animator)",
        l_unlockTrophy5: "Trophy + Prize (Party Toolbox, Scribble)",
        l_unlockSong0: "Bonus Song (Butterfly - Smile.dk)",
        l_unlockSong1: "Bonus Song (Xepher - Tatsh)",
        l_unlockSong2: "Bonus Song (Billie Jean - Michael Jackson)",
        l_unlockSong3: "Bonus Song (Lost - Kyrn)",
        l_unlockSong4: "Bonus Song (Super Mario RPG - Marta)",
        l_unlockSong5: "Bonus Song (Fury of the Storm - Dragonforce)",
        l_unlockAvatarUpgrade0: "Avatar Upgrade (Alien Skin Colors)",
        l_unlockAvatarUpgrade1: "Avatar Upgrade (Sabre Glowsticks)",
        l_unlockAvatarUpgrade2: "Avatar Upgrade (Pulsar Glowsticks)",
        l_unlockAvatarUpgrade3: "Avatar Upgrade (Sparker Glowsticks)",

        l_playerEntry: "{0}. {1}", // A RecordList entry

        l_summaryList0: "Here and Now",
        l_summaryList1: "Daily Best",
        l_summaryList2: "Monthly Best",

        l_summaryHeader: "Stats for {0}",
        l_score: "Score",
        l_accuracy: "{0}%",
        l_bestCombo: "Max Combo",
        l_tier0: "OKs",
        l_tier1: "Goods",
        l_tier2: "Perfects",
        l_holds: "Holds",
        l_boos: "Boos",
        l_misses: "Misses",
        l_recordedOn: "Recorded on",
        l_showProfile: "Show Profile",
        l_difficulty: "Difficulty",
        l_difficulty0: "Beginner",
        l_difficulty1: "Light",
        l_difficulty2: "Standard",
        l_difficulty3: "Heavy",
        l_difficulty4: "GURU",
        l_difficultyChange: "(Change)",
        l_unlockedAt: " (Unlocked at level {0})",
        t_flag: "Home country: {0}",

        m_feedFacebook: "I just scored {0} dancing to {1} on Synced Beat!",
        m_feedTwitter: "I just scored {0} dancing to {1} on Synced Beat \u266B http://bit.ly/PlayBeat",
        l_share: "Hot score? One click challenge your friends from:",
        l_facebook: "Facebook",
        l_twitter: "Twitter",
        l_whirled: "Synced",

//        m_holdGood: "Sweet!",
//        m_holdBad: "Hold it",
//        m_missed: "Missed",
//        m_boo: "Boo",
//        m_tier0: "OK",
//        m_tier1: "Good",
//        m_tier2: "Perfect!",
        l_combo: "{0} Combo",

        l_level: "Level {0}",

        m_invite: "Show me your moves!",
        m_bye: "Thanks for playing Synced Beat. Come back soon, you dig?",
        m_noDifficulty: "No {0} difficulty for {2}. Adjusting to {1} instead.",
        m_startedNoCredit: "{0} spins up {1} by {2}",
        m_started: "{0} spins up {1} by {2} (Steps by {3})",

        t_locate: "Find active clubs",
        t_addMusic: "Add music from your stuff",
        t_invite: "Invite friends",
        t_quit: "Quit Synced Beat",
        t_close: "Close",

        t_promo: "Visit our friends at DDREncore.com",

        e_pack_error: "{1} - Error loading level pack: {0}.",
        e_missing_protocol: "{0} - Protocol (either http:// or pack://) is missing from song!",
        e_io_error: "{1} - An IO error occured while loading the song: {0}.",
        e_security_error: "{1} - A security error occured while loading the song: {0}.",
        e_unsupported_protocol: "{0} - Only http:// or pack:// protocols are supported."
    });
}

}
