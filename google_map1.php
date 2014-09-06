
    <script>
// This example displays an address form, using the autocomplete1 feature
// of the Google Places API to help users fill in the information.


var componentForm1 = {
  d_street_number: 'short_name',
  d_route: 'long_name',
  d_locality: 'long_name',
  d_administrative_area_level_1: 'short_name',
  d_country: 'long_name',
  d_postal_code: 'short_name'
};

function initialize1() {
  // Create the autocomplete1 object, restricting the search
  // to geographical location types.
  autocomplete1 = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('autocomplete1')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete1, 'place_changed', function() {
    fillInAddress1();
  });
}

// [START region_fillform]
function fillInAddress1() {
  // Get the place details from the autocomplete object.
  var place = autocomplete1.getPlace();

  for (var component in componentForm1) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm1[addressType]) {
      var val = place.address_components[i][componentForm1[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate1() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      autocomplete1.setBounds(new google.maps.LatLngBounds(geolocation,
          geolocation));
    });
  }
}
// [END region_geolocation]

    </script>

    <style>
      #locationField, #controls {
        position: relative;
        width: 480px;
      }
      #autocomplete1 {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
      }
      .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
      }
      #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
      }
      #address td {
        font-size: 10pt;
      }
      .field {
        width: 99%;
      }
      .slimField {
        width: 80px;
      }
      .wideField {
        width: 200px;
      }
      #locationField {
        height: 20px;
        margin-bottom: 2px;
      }
    </style>

  
    <div id="locationField">
      <input id="autocomplete1" placeholder="Enter your address" onFocus="geolocate()" type="text"></input>
    </div>

    <table id="address1">
      <tr>
        <td class="label">Street address</td>
        <td class="slimField"><input class="form-control" id="d_street_number" name="d_street_number" disabled="true"></input></td>
        <td class="wideField" colspan="2"><input class="field" id="d_route" name="d_route"  disabled="true"></input></td>
      </tr>
      <tr>
        <td class="label">City</td>
        <td class="wideField" colspan="3"><input class="form-control" id="d_locality" name="d_city" disabled="true"></input></td>
      </tr>
      <tr>
        <td class="label">State</td>
        <td class="slimField"><input class="form-control" id="d_administrative_area_level_1" name="d_state" disabled="true"></input></td>
        <td class="label">Zip code</td>
        <td class="wideField"><input class="form-control" id="d_postal_code" name="d_postal_code" disabled="true"></input></td>
      </tr>
      <tr>
        <td class="label">Country</td>
        <td class="wideField" colspan="3"><input class="form-control"  name="d_country" id="d_country" disabled="true"></input></td>
      </tr>
    </table>


