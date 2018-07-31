<?php

// Page Meta
set( 'page.title', 'Scene Switcher' );

// Get streamer list
if ( isset($_GET['streamers']) ) {

    // Sanitize input
    $gump = new GUMP();
    $data = $gump->sanitize($_GET);

    // Explode by comma
    $streamers = explode(',', trim($data['streamers'], ','));
    set( 'streamers', $streamers );

}

get_header(); ?>

    <div id="canvas" class="ss">
        <div class="ss__player" id="player-top-left">
            <div class="ss__player-embed"></div>
            <span class="ss__player-username"></span>
            <i class="ss__player-volume fas fa-volume-up" data-player-id="0"></i>
        </div>
        <div class="ss__player ss__player--right" id="player-top-right">
            <div class="ss__player-embed"></div>
            <span class="ss__player-username"></span>
            <i class="ss__player-volume fas fa-volume-up" data-player-id="1"></i>
        </div>
        <div class="ss__center"></div>
        <div class="ss__player" id="player-bottom-left">
            <div class="ss__player-embed"></div>
            <span class="ss__player-username"></span>
            <i class="ss__player-volume fas fa-volume-up" data-player-id="2"></i>
        </div>
        <div class="ss__player ss__player--right" id="player-bottom-right">
            <div class="ss__player-embed"></div>
            <span class="ss__player-username"></span>
            <i class="ss__player-volume fas fa-volume-up" data-player-id="3"></i>
        </div>
    </div>

    <div id="controls" class="ssc dg cols-2 gap-50 mt-12 p-12">

        <div id="switcher-controls" class="dg cols-2 gap-24">
            <div class="span-1">
                <label class="dib br-3 c-white py-4 ls-1 uppercase fs-14 mb-2" for="player-top-left">Top Left</label><br>
                <input class="db w-full br-3 c-white bgc-white-1 py-8 px-10" name="player-top-left" type="text" placeholder="Enter a Twitch username">
                <button class="switch-player bgc-warning c-white px-8 py-6 fs-13 uppercase mt-4 br-3" data-player-id="0">Update</button>
            </div>
            <div class="span-1 tr">
                <label class="dib br-3 c-white py-4 ls-1 uppercase fs-14 mb-2" for="player-top-right">Top Right</label><br>
                <input class="br-3 w-full c-white bgc-white-1 py-8 px-10" name="player-top-right" type="text" placeholder="Enter a Twitch username">
                <button class="switch-player bgc-warning c-white px-8 py-6 fs-13 uppercase mt-4 br-3" data-player-id="1">Update</button>
            </div>
            <div class="span-1">
                <label class="dib br-3 c-white py-4 ls-1 uppercase fs-14 mb-2" for="player-bottom-left">Bottom Left</label><br>
                <input class="br-3 w-full c-white bgc-white-1 py-8 px-10" name="player-bottom-left" type="text" placeholder="Enter a Twitch username">
                <button class="switch-player bgc-warning c-white px-8 py-6 fs-13 uppercase mt-4 br-3" data-player-id="2">Update</button>
            </div>
            <div class="span-1 tr">
                <label class="dib br-3 c-white py-4 ls-1 uppercase fs-14 mb-2" for="player-bottom-right">Bottom Right</label><br>
                <input class="br-3 w-full c-white bgc-white-1 py-8 px-10" name="player-bottom-right" type="text" placeholder="Enter a Twitch username">
                <button class="switch-player bgc-warning c-white px-8 py-6 fs-13 uppercase mt-4 br-3" data-player-id="3">Update</button>
            </div>
        </div>

        <div id="switcher-list" class="dg cols-3 gap-24">
            <div class="span-1">
                <label class="dib br-3 c-white py-4 ls-1 uppercase fs-14 mb-4" for="player">Add streamer</label><br>
                <input class="br-3 w-full c-white bgc-white-1 py-8 px-12" name="player" type="text" placeholder="Enter a Twitch username">
                <button class="add-quick-streamer bgc-warning c-white px-8 py-6 fs-13 uppercase mt-4 br-3">Add</button>
            </div>
            <div class="span-2">
                <h3 class="br-3 c-white py-4 ls-1 uppercase fs-14 mb-4 mt-0">Streamers</h3>

                <div id="streamer-list" class="c-white-8 fs-14">

                    <!-- template -->
                    <div class="streamer dg cols-10 ai-center bs bw-0 bwb-1 bc-dark-grey py-8">
                        <button class="delete-streamer tl span-1 bgc-transparent c-negative-3 c-negative-h"><i class="fas fa-times"></i></button>
                        <div class="streamer__name span-4 truncate">thedragonfeeney</div>
                        <div class="span-5 tr">
                            <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="thedragonfeeney" data-player-id="0">TL</button>
                            <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="thedragonfeeney" data-player-id="1">TR</button>
                            <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="thedragonfeeney" data-player-id="2">BL</button>
                            <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="thedragonfeeney" data-player-id="3">BR</button>
                        </div>
                    </div>

                    <?php if ( $streamers = get('streamers') ): ?>
                        <?php foreach ( $streamers as $str ): ?>

                            <div class="streamer dg cols-10 ai-center bs bw-0 bwb-1 bc-dark-grey py-8">
                                <button class="delete-streamer tl span-1 bgc-transparent c-negative-3 c-negative-h"><i class="fas fa-times"></i></button>
                                <div class="streamer__name span-4 truncate"><?= $str ?></div>
                                <div class="span-5 tr">
                                    <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="<?= $str ?>" data-player-id="0">TL</button>
                                    <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="<?= $str ?>" data-player-id="1">TR</button>
                                    <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="<?= $str ?>" data-player-id="2">BL</button>
                                    <button class="quick-switch-btn bgc-white-1 br-3 c-white fs-13 ml-4 ls-1 py-2 bgc-warning-h" data-name="<?= $str ?>" data-player-id="3">BR</button>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

<?php get_footer(); ?>
