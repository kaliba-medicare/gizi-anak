// Custom Control: Modern Dropdown
    const selectorControl = L.control({ position: 'topleft' });
    selectorControl.onAdd = function() {
        const div = L.DomUtil.create('div', 'leaflet-control-custom');
        div.innerHTML = `
            <label for="boundary-selector">Batas Wilayah</label>
            <select id="boundary-selector">
                <option value="kecamatan">Batas Kecamatan</option>
                <option value="desa">Batas Desa</option>
            </select>
        `;
        return div;
    };
    selectorControl.addTo(map);