/* eslint-disable react/prop-types */

import React, { useRef, useEffect, useState } from 'react';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { load } from 'wpify-mapy-cz';

const MapyCz = (props) => {
  const {
    attributes,
    setAttributes,
    className,
  } = props;

	const [maps, setMaps] = useState();
	const mapref = useRef();
	const [mapycz, setMapycz] = useState();

	const mapId = parseInt(attributes.map_id, 10);

	useEffect(() => {
		apiFetch({ path: wpify_mapy_cz_block.maps_api, method: 'GET' }).then(setMaps);
	}, [setMaps]);

	useEffect(() => {
		const map = maps ? maps.find(m => m.id === mapId) : null;

		if (!mapycz && map) {
			load({
				element: mapref.current,
				center: { latitude: map.latitude, longitude: map.longitude },
				markers: map.markers,
				zoom: map.zoom,
			}, setMapycz);
		} else if (map) {
			mapycz.setZoom(map.zoom);
			mapycz.setCenter({ latitude: map.latitude, longitude: map.longitude });
			mapycz.removeMarkers();
			mapycz.addMarkers(map.markers);
		}
	}, [mapycz, setMapycz, maps, mapId]);

  if (!maps) {
    return (
      <h4>Loading maps list...</h4>
    )
  }

  const mapsOptions = [
    {
      label: __('Select map', 'wpify-mapy-cz'),
      value: ''
    }
  ];

  maps.forEach(item => mapsOptions.push({ value: item.id, label: item.title }));

  return (
    <div className={className}>
      <SelectControl
        label={__('WPify Mapy.cz:', 'wpify-mapy-cz')}
        options={mapsOptions}
        onChange={(map_id) => setAttributes({ map_id })}
        value={attributes.map_id}
      />
      <div ref={mapref} style={{ height: '400px', display: mapId ? 'block' : 'none' }} />
    </div>
  );
};

registerBlockType('wpify/mapy-cz', {
  title: __('WPify Mapy.cz', 'wpify-mapy-cz'),
  icon: (
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M 10.611 0.03 C 7.915 0.438 5.803 1.41 3.941 3.087 C 1.292 5.467 -0.08 8.681 0.004 12.284 C 0.022 13.025 0.059 13.748 0.096 13.905 C 0.291 14.831 0.356 15.091 0.569 15.758 C 1.134 17.527 2.079 19.055 3.515 20.481 C 5.34 22.297 7.174 23.288 9.731 23.834 C 10.778 24.057 13.242 24.057 14.316 23.825 C 17.559 23.13 20.254 21.324 22.024 18.657 C 22.876 17.369 23.626 15.554 23.821 14.276 C 23.849 14.072 23.914 13.72 23.951 13.488 C 24.034 13.044 24.006 10.673 23.914 10.2 C 23.876 10.043 23.812 9.709 23.765 9.459 C 23.497 8.144 22.802 6.523 21.959 5.236 C 21.394 4.383 19.671 2.651 18.809 2.077 C 17.447 1.169 15.863 0.512 14.27 0.188 C 13.584 0.049 11.185 -0.053 10.611 0.03 Z M 9.712 5.384 C 9.888 5.458 10.148 5.652 10.287 5.819 C 10.527 6.097 11.139 7.273 11.574 8.301 C 12.149 9.626 12.649 10.737 12.695 10.784 C 12.769 10.867 14.585 8.941 15.511 7.792 C 15.595 7.69 15.817 7.403 16.012 7.162 C 16.206 6.921 16.41 6.727 16.475 6.727 C 16.679 6.727 17.42 7.616 17.577 8.061 C 17.716 8.44 17.735 8.746 17.688 10.228 C 17.67 11.182 17.623 12.127 17.605 12.331 C 17.586 12.534 17.549 13.322 17.512 14.09 C 17.438 15.878 17.401 16.221 17.234 16.545 C 17.077 16.841 16.679 17.017 16.382 16.906 C 15.789 16.693 15.622 15.776 15.641 12.942 C 15.65 11.367 15.622 10.756 15.548 10.756 C 15.484 10.756 15.113 11.071 14.715 11.451 C 13.612 12.525 12.806 13.192 12.464 13.34 C 11.917 13.562 11.797 13.433 10.963 11.756 C 10.074 9.95 10.018 9.885 9.907 10.58 C 9.731 11.719 9.295 13.664 9.045 14.424 C 8.61 15.721 7.897 17.138 7.535 17.416 C 7.22 17.647 6.387 17.962 5.757 18.073 C 5.33 18.157 5.303 18.268 6.099 16.36 C 7.507 12.951 8.11 10.237 8.073 7.421 C 8.063 6.551 8.091 5.773 8.137 5.68 C 8.323 5.291 9.129 5.143 9.712 5.384 Z" fill="#61c222"/>
    </svg>
  ),
  category: 'embed',
  edit: MapyCz,
  save: () => null,
});
