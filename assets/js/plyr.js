if (typeof psm_data === 'undefined') {
	psm_data = {
		debug: false,
		use_native_player_safari: false,
	};
} else {
	psm_data.debug = psm_data.debug === '1';
	psm_data.use_native_player_safari = psm_data.use_native_player_safari === '1';
}

window.addEventListener('DOMContentLoaded', function () {
	const players = Plyr.setup(document.querySelectorAll('.drppsm-player,.drppsm-video-player'), {
		debug: psm_data.debug,
		enabled: psm_data.use_native_player_safari ? (!/Safari/.test(navigator.userAgent) || (/Safari/.test(navigator.userAgent) && /Chrome|OPR/.test(navigator.userAgent))) : true,
	});

	for (let p in players) {
		if (players.hasOwnProperty(p)) {
			players[p].on('loadedmetadata ready', function (event) {
				let instance = event.detail.plyr;

				if (instance.elements.original.dataset.plyr_seek !== undefined) {
					instance.currentTime = parseInt(instance.elements.original.dataset.plyr_seek);
					instance.embed.setCurrentTime(parseInt(instance.elements.original.dataset.plyr_seek));
				}
			});
		}
	}
});
