document.addEventListener('DOMContentLoaded', function() {

    var Switcher = function(canvas, controls) {

        this.canvas = canvas; // DOM element for the main canvas
        this.controls = controls;
        this.players = []; // Players array
        this.streamers = []; // Streamers object

        /**
         * Create Players
         *
         * Creates an array with 4 players
         */
        this.createPlayers = function() {
            this.players.push( new Player(this.canvas.querySelector('#player-top-left')) );
            this.players.push( new Player(this.canvas.querySelector('#player-top-right')) );
            this.players.push( new Player(this.canvas.querySelector('#player-bottom-left')) );
            this.players.push( new Player(this.canvas.querySelector('#player-bottom-right')) );
        }

        /**
         * Listen for Controls
         */
        this.controlEvents = function() {

            // Get buttons
            var btns = this.controls.querySelectorAll('button.switch-player');

            // Attach events
            btns.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    var button, playerID, input, player, streamer;

                    // Get button
                    button = event.target;

                    // Get input
                    input = button.previousElementSibling;

                    // If the input or value is empty, don't do anything
                    if ( !input || input.value == '' ) {
                        return;
                    }

                    // Create Streamer
                    streamer = new Streamer(input.value);

                    // Get player ID
                    playerID = button.getAttribute('data-player-id');

                    // Get player
                    player = this.players[playerID];

                    // Create player
                    player.createPlayer(streamer);

                }.bind(this));
            }.bind(this));

            // Get audio control
            var audioBtns = this.canvas.querySelectorAll('.ss__player-volume');

            // Attach events
            audioBtns.forEach(function(audioBtn) {
                audioBtn.addEventListener('click', function(event) {
                    event.preventDefault();
                    var button, playerID, current;

                    // Get button
                    button = event.target;

                    // Get player ID
                    playerID = button.getAttribute('data-player-id');

                    // Mute them all
                    for (i = 0; i < this.players.length; i++) {
                        if ( this.players[i].embed ) {
                            this.players[i].embed.setVolume(0);
                        }
                    }

                    // Get the current player
                    current = this.players[playerID];

                    // Turn this one up
                    current.embed.setVolume(1);

                    // Remove highlighted icon for all others
                    for (i = 0; i < audioBtns.length; i++) {
                        audioBtns[i].classList.remove('is-active');
                    }

                    // Highlight the icon
                    button.classList.add('is-active');

                }.bind(this));
            }.bind(this));

            // Get list control
            var listBtns = document.querySelectorAll('.quick-switch-btn');

            // Attach events
            listBtns.forEach(function(listBtn) {
                listBtn.addEventListener('click', this.assignPlayer.bind(this));
            }.bind(this));

            // Add quick streamer to the list
            var addStreamer = document.querySelector('.add-quick-streamer');

            addStreamer.addEventListener('click', function(event) {
                event.preventDefault();
                var input;

                // Get button
                button = event.target;

                // Get input
                input = button.previousElementSibling;

                console.log(input.value);

                // If the input or value is empty, don't do anything
                if ( !input || input.value == '' ) {
                    return;
                }

                // Get Streamer list
                var streamerList = document.getElementById('streamer-list');

                // Get the first item so we can clone it
                var template = streamerList.querySelector('.streamer');

                // Clone it
                var newStreamer = template.cloneNode(true);

                // Update the name
                var nameLabel = newStreamer.querySelector('.streamer__name');
                nameLabel.innerHTML = input.value;

                // Update buttons
                var buttons = newStreamer.querySelectorAll('.quick-switch-btn');

                for (i = 0; i < buttons.length; i++) {
                    buttons[i].setAttribute('data-name', input.value);
                    buttons[i].addEventListener('click', this.assignPlayer.bind(this));
                }

                // Add listener for deleting this streamer
                var deleteStreamer = newStreamer.querySelector('.delete-streamer');
                deleteStreamer.addEventListener('click', this.deleteStreamer.bind(this));

                // Add to the list
                streamerList.appendChild(newStreamer);

            }.bind(this));

            // Delete streamer from list
            deleteBtns = document.querySelectorAll('.delete-streamer');

            // Attach events
            deleteBtns.forEach(function(deleteBtn) {
                deleteBtn.addEventListener('click', this.deleteStreamer.bind(this));
            }.bind(this));

        }

        /**
         * Assign Player
         */
        this.assignPlayer = function(event) {
            event.preventDefault();
            var button, playerID, input, player, streamer;

            // Get button
            button = event.target;

            // Get streamer name
            streamerName = button.getAttribute('data-name');

            // Create Streamer
            streamer = new Streamer(streamerName);

            // Get player ID
            playerID = button.getAttribute('data-player-id');

            // Get player
            player = this.players[playerID];

            // Create player
            player.createPlayer(streamer);
        }

        /**
         * Delete Streamer
         */
        this.deleteStreamer = function(event) {
            event.preventDefault();

            // Delete the streamer from the list
            event.target.parentNode.remove();

        }

        /**
         * Init
         */
        this.init = function() {
            this.createPlayers();
            this.controlEvents();
        }

    }

    var Player = function(element) {
        this.element = element;
        this.isActive = 0;
        this.isMuted = 1;
        this.embed = null;

        /**
         * Set Label
         */
        this.setLabel = function(username) {
            var label = this.element.querySelector('.ss__player-username');
            label.innerHTML = username;
        }

        /**
        * Create Player
        *
        * Places a Twitch player on the page
        */
        this.createPlayer = function(streamer) {

            // Player already running?
            if ( this.embed ) {
                this.destroyPlayer();
            }

            // Setup player options
            var options = {
                width: 631,
                height: 355,
                channel: streamer.username
            };

            // Create the player
            var embedElement = this.element.querySelector('.ss__player-embed');
            this.embed = new Twitch.Player(embedElement, options);

            // Volume
            var volume = (this.isMuted ? 0 : 1);
            this.embed.setVolume(volume);

            // Set name label
            this.setLabel(streamer.username);

        }

        /**
         * Destroy player
         */
        this.destroyPlayer = function() {

            // Reset the embed
            this.embed = null;

            // Remove the iframe
            var iframe = this.element.querySelector('.ss__player-embed');
            iframe.innerHTML = '';

        }


    }

    var Streamer = function(username, muted = 1) {
        this.username = username;
        this.isMuted = muted;
    }

    var sceneCanvas = document.getElementById('canvas');
    var sceneControls = document.getElementById('switcher-controls');
    var sceneSwitcher = new Switcher(sceneCanvas, sceneControls);
    sceneSwitcher.init();

});
