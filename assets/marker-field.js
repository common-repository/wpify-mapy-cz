import React, { useEffect, useRef, useState } from 'react';
import classnames from 'classnames';
import { load } from 'wpify-mapy-cz';
import { addFilter, applyFilters } from '@wordpress/hooks';
import { __, sprintf } from '@wordpress/i18n';

const MarkerField = (props) => {
	const { id, className, group_level = 0, value = {} } = props;
	const [currentValue, setCurrentValue] = useState(value);
	const mapref = useRef();
	const suggestref = useRef();
	const [mapycz, setMapycz] = useState();

	const {
		description = '',
		latitude = 0,
		longitude = 0,
		zoom = 12,
	} = currentValue;

	useEffect(() => {
		load({
			element: mapref.current,
			center: { longitude, latitude },
			zoom,
			sync_control: true,
			marker: { longitude, latitude },
			default_controls: true,
		}, setMapycz);
	}, [setMapycz]);

	useEffect(() => {
		if (mapycz) {
			Object.values(mapycz.markers).forEach((marker) => {
				marker.decorate(SMap.Marker.Feature.Draggable);
			});

			const signals = mapycz.getMap().getSignals();

			signals.addListener(window, 'marker-drag-stop', (e) => {
				const coords = e.target.getCoords();
				setCurrentValue({ ...currentValue, longitude: coords.x, latitude: coords.y });
			});

			mapycz.addSuggest({ input: suggestref.current }, (place) => {
				setCurrentValue({
					...currentValue,
					longitude: place.data.longitude,
					latitude: place.data.latitude,
					address: place.data.title,
				});
			});
		}
	}, [mapycz, setCurrentValue]);

	useEffect(() => {
		if (mapycz) {
			mapycz.setCenter({ latitude, longitude });

			if (Object.values(mapycz.markers).length === 0) {
				mapycz.addMarker({ longitude, latitude });
			}

			Object.values(mapycz.markers).forEach((marker) => {
				marker.setCoords(new SMap.Coords(longitude, latitude));
				marker.decorate(SMap.Marker.Feature.Draggable);
			});
		}
	}, [mapycz, latitude, longitude]);

	useEffect(() => {
		if (mapycz) {
			const handleMapChange = (e) => {
				const newZoom = e.target.getZoom();

				setCurrentValue((currentValue) => ({
					...currentValue,
					zoom: newZoom
				}));
			};

			mapycz.getMap().getSignals().addListener(window, 'map-redraw', handleMapChange);
		}
	}, [mapycz, setCurrentValue]);

	return (
		<React.Fragment>
			{group_level === 0 && ( // We need to have the input with the name only if not in group
				<input type="hidden" name={id} value={JSON.stringify(currentValue)}/>
			)}
			{applyFilters('wpify_mapy_cz_marker_form_begin', null, { currentValue, setCurrentValue, mapycz })}
			<div style={{ marginBottom: '1rem' }}>
				<input
					id={id}
					type="text"
					ref={suggestref}
					className={classnames('regular-text', className)}
					style={{ width: '100%' }}
					placeholder={__('Type to search...', 'wpify-mapy-cz')}
				/>
			</div>
			{latitude && longitude && (
				<div style={{ marginBottom: '1rem' }}>
					<small>{sprintf(__('latitude: %s, longitude: %s', 'wpify-mapy-cz'), latitude, longitude)}</small>
				</div>
			)}
			{applyFilters('wpify_mapy_cz_marker_form_before_map', null, { currentValue, setCurrentValue, mapycz })}
			<div className="mapycz" style={{ height: '400px', marginBottom: '1rem' }} ref={mapref}/>
			{applyFilters('wpify_mapy_cz_marker_form_after_map', null, { currentValue, setCurrentValue, mapycz })}
			<div style={{ marginBottom: '1rem' }}>
				<label>
					{__('Description:', 'wpify-mapy-cz')}
					<br/>
					<textarea
						value={description}
						onChange={(e) => setCurrentValue({ ...currentValue, description: e.target.value })}
						style={{ width: '100%' }}
						rows={5}
					/>
				</label>
			</div>
			{applyFilters('wpify_mapy_cz_marker_form_end', null, { currentValue, setCurrentValue, mapycz })}
		</React.Fragment>
	);
};

addFilter('wcf_field_mapycz_marker', 'wpify-mapy-cz', Component => MarkerField);
