document.addEventListener('DOMContentLoaded', function () {

    const gpxInput = document.getElementById('gpx-input');

    if (gpxInput) {
        gpxInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                try {
                    const parser = new gpxParser();
                    parser.parse(event.target.result);

                    if (parser.tracks.length > 0) {
                        const track = parser.tracks[0];

                        const elevationData = track.points.map(p => p.ele);
                        const maxElevation = elevationData.length > 0 
                            ? Math.max(...elevationData) 
                            : 0;

                        const distance = (track.distance.total / 1000).toFixed(2); // km
                        const elevation = Math.round(track.elevation.pos || 0);    // m

                        document.getElementById('distance-display').innerText = "Distance: " + distance + " km";
                        document.getElementById('elevation-display').innerText = "Elevation: " + elevation + " m";
                        document.getElementById('file-status').innerText = "✅ File " + file.name + " loaded.";

                        document.getElementById('distance-hidden').value = distance;
                        document.getElementById('elevation-hidden').value = elevation;
                        document.getElementById('max-elevation-hidden').value = maxElevation.toFixed(0);
                    } else {
                        alert("No tracks found in GPX!");
                    }
                } catch (err) {
                    console.error("Error:", err);
                }
            };
            reader.readAsText(file);
        });
    }
});

document.getElementById('photo-input').addEventListener('change', function() {
    const name = this.files[0] ? this.files[0].name : "null";
    document.getElementById('photo-status').innerText = "✅ Photo " + name;
});