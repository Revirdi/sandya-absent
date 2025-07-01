<div>
    <label for="location_name" class="block mb-2 text-sm font-medium $location">Location Name</label>
    <input type="text" id="location_name" name="location_name" maxlength="50" required
        value="{{ old('location_name', $location->location_name ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
</div>
<div>
    <label for="address" class="block mb-2 text-sm font-medium $location">Address</label>
    <textarea id="address"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
        name="address">{{ old('address', $location->address ?? '') }}</textarea>
</div>
<div>
    <label for="latitude" class="block mb-2 text-sm font-medium $location">Latitude</label>
    <input type="text" id="latitude" name="latitude" maxlength="50" required
        value="{{ old('latitude', $location->latitude ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
</div>
<div>
    <label for="longitude" class="block mb-2 text-sm font-medium $location">longitude</label>
    <input type="text" id="longitude" name="longitude" maxlength="50" required
        value="{{ old('longitude', $location->longitude ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
</div>
