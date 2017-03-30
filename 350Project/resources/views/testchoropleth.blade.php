<!DOCTYPE html>
<html>
<head>
    <style>
        .active { fill: blue !important;}
        /*.datamaps-key dt, .datamaps-key dd {float: none !important;}
        .datamaps-key {right: -50px; top: 0;}*/
    </style>
</head>
<body>

<div id="container1" style="border:1px dotted blue; width: 700px; height: 475px; position: relative;"></div>

<script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>
<script src="js/datamaps.world.min.js"></script>

<script>
    // example data from server
	 var series = [
        ["CHN",100],["USA",66],["DEU",58],["JPN",27],["KOR",23],["FRA",21],
        ["GBR",20],["ITA",20],["CAN",18],["MEX",17],["RUS",15],["IND",12],
        ["SAU",9],["BRA",8],["AUS",8],["ZAF",3],["TUR",6],["IDN",7],
        ["ARG",2]];
    // Datamaps expect data in format:
    // { "USA": { "fillColor": "#42a844", numberOfWhatever: 75},
    //   "FRA": { "fillColor": "#8dc386", numberOfWhatever: 43 } }
    var dataset = {};
    // We need to colorize every country based on "numberOfWhatever"
    // colors should be uniq for every value.
    // For this purpose we create palette(using min/max series-value)
    var onlyValues = series.map(function(obj){ return obj[1]; });
    var minValue = Math.min.apply(null, onlyValues),
            maxValue = Math.max.apply(null, onlyValues);
    // create color palette function
    // color can be whatever you wish
    var paletteScale = d3.scale.linear()
            .domain([minValue,maxValue])
            .range(["#EFEFFF","#02386F"]); // blue color
    // fill dataset in appropriate format
    series.forEach(function(item){ //
        // item example value ["USA", 70]
        var iso = item[0],
                value = item[1];
        dataset[iso] = { numberOfThings: value, fillColor: paletteScale(value) };
    });
    // render map
    new Datamap({
        element: document.getElementById('container1'),
        projection: 'mercator', // big world map
        // countries don't listed in dataset will be painted with this color
        fills: { defaultFill: '#F5F5F5' },
        data: dataset,
        geographyConfig: {
            borderColor: '#DEDEDE',
            highlightBorderWidth: 2,
            // don't change color on mouse hover
            highlightFillColor: function(geo) {
                return geo['fillColor'] || '#F5F5F5';
            },
            // only change border
            highlightBorderColor: '#B7B7B7',
            // show desired information in tooltip
            popupTemplate: function(geo, data) {
                // don't show tooltip if country don't present in dataset
                if (!data) { return ; }
                // tooltip content
                return ['<div class="hoverinfo">',
                    '<strong>', geo.properties.name, '</strong>',
                    '<br>Count: <strong>', data.numberOfThings, '</strong>',
                    '</div>'].join('');
            }
        }
    });
</script>

</body>
</html>
