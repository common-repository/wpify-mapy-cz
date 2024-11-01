import React, { useEffect, useRef, useState } from 'react';
import { load } from 'wpify-mapy-cz';
import Select from 'react-select';
import { addFilter, applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { TextControl } from '@wordpress/components';

const MapField = (props) => {
	const { id, group_level = 0, value = {} } = props;
	const [currentValue, setCurrentValue] = useState(value);
	const mapref = useRef();
	const [mapycz, setMapycz] = useState();
	const [allMarkers, setAllMarkers] = useState();

	const layer_types = {
		DEF_BASE: __('Basic map', 'wpify-mapy-cz'),
		DEF_OPHOTO: __('Ortho map', 'wpify-mapy-cz'),
		DEF_HYBRID: __('Hybrid map', 'wpify-mapy-cz'),
		DEF_TURIST: __('Turist map', 'wpify-mapy-cz'),
		DEF_TURIST_WINTER: __('Winter turist map', 'wpify-mapy-cz'),
	};

	const {
		auto_center_zoom = false,
		description = '',
		width = '100%',
		height = '400px',
		latitude = 0,
		longitude = 0,
		show_info_window = false,
		zoom = 18,
		markers = [],
		layer_type = 'DEF_BASE',
		disable_scroll = false,
	} = currentValue;

	useEffect(() => {
		load({
			element: mapref.current,
			center: { longitude, latitude },
			zoom,
			sync_control: true,
			default_controls: true,
			mapType: layer_type,
		}, setMapycz);
	}, [load, setMapycz]);

	useEffect(() => {
		if (mapycz) {
			const handleMapChange = (e) => {
				const newCenter = e.target.getCenter();
				const newLatitude = newCenter.y;
				const newLongitude = newCenter.x;
				const newZoom = e.target.getZoom();

				setCurrentValue((currentValue) => ({
					...currentValue,
					latitude: newLatitude,
					longitude: newLongitude,
					zoom: newZoom
				}));
			};

			mapycz.getMap().getSignals().addListener(window, 'map-redraw', handleMapChange);
		}
	}, [mapycz, setCurrentValue]);

	useEffect(() => {
		apiFetch({ path: wpify_mapy_cz.markers_api, method: 'GET' }).then(setAllMarkers);
	}, []);

	useEffect(() => {
		if (mapycz) {
			mapycz.getMap().addDefaultLayer(SMap[layer_type]).enable();
		}
	}, [mapycz, layer_type]);

	useEffect(() => {
		if (mapycz && allMarkers) {
			mapycz.removeMarkers();
			mapycz.addMarkers(
				markers
					.map(markerId => allMarkers.find(marker => marker.id === markerId))
					.filter(Boolean)
			);

			if (auto_center_zoom) {
				mapycz.autoCenterZoom();
			}
		}
	}, [mapycz, markers, allMarkers, auto_center_zoom]);

	useEffect(() => {
		if (mapycz && auto_center_zoom) {
			mapycz.autoCenterZoom();
		}
	}, [mapycz, auto_center_zoom]);

	const markersOptions = allMarkers?.map((marker) => ({
		value: marker.id,
		label: marker.title,
	}));

	const selectedMarkers = markersOptions?.filter((option) => markers?.includes(option.value));

	const handleLayerTypeChange = (type) => setCurrentValue({ ...currentValue, layer_type: type.value });

	const handleDescriptionChange = (event) => setCurrentValue({ ...currentValue, description: event.target.value });

	const handleMarkersChange = (maybeMarkers = []) => {
		setCurrentValue({
			...currentValue,
			markers: Array.isArray(maybeMarkers) ? maybeMarkers.map(marker => marker.value) : [],
		});
	};

	const handleCenterZoomByMarkers = (event) => setCurrentValue({ ...currentValue, auto_center_zoom: event.target.checked });

	const handleShowInfoWindow = (event) => setCurrentValue({ ...currentValue, show_info_window: event.target.checked });

	const handleWidthChange = (width) => setCurrentValue({ ...currentValue, width });

	const handleHeightChange = (height) => setCurrentValue({ ...currentValue, height });

	const handleDisableScroll = (event) => setCurrentValue({ ...currentValue, disable_scroll: event.target.checked });

	return (
		<React.Fragment>
			{group_level === 0 && ( // We need to have the input with the name only if not in group
				<input type="hidden" name={id} value={JSON.stringify(currentValue)}/>
			)}
			{applyFilters('wpify_mapy_cz_map_form_begin', null, { currentValue, setCurrentValue, mapycz })}
			<div className="mapycz" style={{ height: '400px', marginBottom: '1rem' }} ref={mapref}/>
			{applyFilters('wpify_mapy_cz_map_after_map', null, { currentValue, setCurrentValue, mapycz })}
			<div style={{ marginBottom: '1rem' }}>
				<label>
					<strong>
						{__('Description:', 'wpify-mapy-cz')}
					</strong>
					<br/>
					<textarea
						value={description}
						onChange={handleDescriptionChange}
						style={{ width: '100%' }}
						rows={5}
					/>
				</label>
			</div>
			{applyFilters('wpify_mapy_cz_map_after_description', null, { currentValue, setCurrentValue, mapycz })}
			<label style={{ display: 'block', marginTop: '10px' }}>
				<div style={{ marginBottom: '10px' }}>
					<strong>{__('Display type:', 'wpify-mapy-cz')}</strong>
				</div>
				<Select
					value={{
						value: layer_type,
						label: layer_types[layer_type],
					}}
					options={Object.keys(layer_types).map(value => ({
						value,
						label: layer_types[value],
					}))}
					onChange={handleLayerTypeChange}
				/>
			</label>
			{applyFilters('wpify_mapy_cz_map_after_diplay_type', null, { currentValue, setCurrentValue, mapycz })}
			{markersOptions?.length > 0 && (
				<label style={{ display: 'block', marginTop: '10px' }}>
					<strong>{__('Markers:', 'wpify-mapy-cz')}</strong>
					<Select
						value={selectedMarkers}
						options={markersOptions}
						onChange={handleMarkersChange}
						isMulti
					/>
				</label>
			)}
			{applyFilters('wpify_mapy_cz_map_after_markers', null, { currentValue, setCurrentValue, mapycz })}
			{markers.length > 0 && (
				<label style={{ display: 'block', marginTop: '10px' }}>
					<input
						type="checkbox"
						checked={auto_center_zoom}
						onChange={handleCenterZoomByMarkers}
					/>
					{__('Set zoom and center by markers', 'wpify-mapy-cz')}
				</label>
			)}
			{applyFilters('wpify_mapy_cz_map_after_auto_center_zoom', null, { currentValue, setCurrentValue, mapycz })}
			<label style={{ display: 'block', marginTop: '10px' }}>
				<input
					type="checkbox"
					checked={show_info_window}
					onChange={handleShowInfoWindow}
				/>
				{__('Show info window', 'wpify-mapy-cz')}
			</label>
			{applyFilters('wpify_mapy_cz_map_after_show_info_window', null, { currentValue, setCurrentValue, mapycz })}
			<div style={{ marginTop: '10px' }}>
				<TextControl
					label={<strong>{__('Width:', 'wpify-mapy-cz')}</strong>}
					value={width}
					onChange={handleWidthChange}
				/>
				<TextControl
					label={<strong>{__('Height:', 'wpify-mapy-cz')}</strong>}
					value={height}
					onChange={handleHeightChange}
				/>
			</div>
			{applyFilters('wpify_mapy_cz_map_after_width_height', null, { currentValue, setCurrentValue, mapycz })}
			<label style={{ display: 'block', marginTop: '10px' }}>
				<input
					type="checkbox"
					checked={disable_scroll}
					onChange={handleDisableScroll}
				/>
				{__('Disable zoom on scroll', 'wpify-mapy-cz')}
			</label>
			{applyFilters('wpify_mapy_cz_map_form_end', null, { currentValue, setCurrentValue, mapycz })}
		</React.Fragment>
	);
};

addFilter('wcf_field_mapycz_map', 'wpify-mapy-cz', Component => MapField);
