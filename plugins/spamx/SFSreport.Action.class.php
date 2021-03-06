<?php

/**
 * File: SFS.Action.class.php
 * This is the Stop Forum Spam Action class for the Geeklog Spam-X plugin
 * Copyright  (C) 2014-2017 Tom Homer  - WebSiteMaster AT cogeco DOT com
 * Licensed under the GNU General Public License


 */

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
    die ('This file can not be used on its own!');
}

// Include Base Classes
require_once($_CONF['path'] . 'plugins/spamx/' . 'BaseCommand.class.php');
require_once($_CONF['path'] . 'plugins/spamx/' . 'SFSbase.class.php');

/**
 * Check posts for Links
 * Due to the way Spam-X works, the SFS Examine class may not have been
 * triggered (when some other module detected the spam first). SFS needs to
 * see all links used in spam posts, though, to accurately detect spam. So
 * this class ensures that SFS sees spam detected by other Spam-X modules, too.
 * based in large part on the works of Dirk Haun, Tom Willet (Spam-X) and Russ Jones (SLV)
 */
class SFSreport extends BaseCommand
{
    public function __construct()
    {
        $this->actionCode = PLG_SPAM_ACTION_DELETE;
    }

    /**
     * Here we do the work
     *
     * @param  string $comment
     * @param  string $permanentLink (since GL 2.2.0)
     * @param  string $commentType (since GL 2.2.0)
     * @param  string $commentAuthor (since GL 2.2.0)
     * @param  string $commentAuthorEmail (since GL 2.2.0)
     * @param  string $commentAuthorURL (since GL 2.2.0)
     * @return int    either PLG_SPAM_NOT_FOUND, PLG_SPAM_FOUND or PLG_SPAM_UNSURE
     * @note As for valid value for $commentType, see system/classes/Akismet.php
     */
    public function execute($comment, $permanentLink = null, $commentType = Geeklog\Akismet::COMMENT_TYPE_COMMENT,
                                     $commentAuthor = null, $commentAuthorEmail = null, $commentAuthorURL = null)
    {
        $this->result = PLG_SPAM_ACTION_DELETE;

        if (isset($GLOBALS['SFS_triggered']) && $GLOBALS['SFS_triggered']) {
            // the Examine class already reported these to SFS
            return PLG_SPAM_FOUND;
        }

        $SFS = new SFSbase();
        $SFS->CheckForSpam($comment);

        return PLG_SPAM_FOUND;
    }
}
