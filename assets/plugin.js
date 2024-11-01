import React, { useEffect, useState, useRef } from 'react';
import ReactDOM from 'react-dom';
import { load } from 'wpify-mapy-cz';
import { applyFilters, doAction } from '@wordpress/hooks';
import { useLazyload } from './hooks';

const Map = (props) => {
	const [mapycz, setMapycz] = useState();
	const { longitude, latitude, zoom, layer_type, markers, auto_center_zoom, width, height } = props;
	const mapref = useRef();
	const { doLoad, lazyload } = useLazyload();

	useEffect(() => {
		lazyload(mapref.current);
	}, []);

	useEffect(() => {
		if (doLoad) {
			const options = {
				//...props,
				element: mapref.current,
				center: { longitude, latitude },
				zoom,
				sync_control: true,
				default_controls: true,
				mapType: layer_type,
				markers,
				auto_center_zoom,
			};

			if (!mapycz) {
				load(applyFilters('wpify-setup-map-options', options, props), setMapycz);
			} else {
				if (props.disable_scroll) {
					mapycz.map.getControls().forEach((control) => {
						if (control instanceof SMap.Control.Mouse) {
							mapycz.map.removeControl(control);
						}
					});

					mapycz.map.addControl(new SMap.Control.Mouse(SMap.MOUSE_PAN | SMap.MOUSE_ZOOM));
				}

				doAction('wpify-map-setup', mapycz, options, props);
			}
		}
	}, [load, mapycz, setMapycz, doLoad]);

	return (
		<div ref={mapref} style={{ width, height }} />
	);
};

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[data-mapycz]').forEach(element => {
		const data = window.wpify_mapy_cz[parseInt(element.dataset.mapycz, 10)];

		ReactDOM.render(<Map {...data} element={element}/>, element);
	});
});
