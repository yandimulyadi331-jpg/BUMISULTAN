<?php

namespace App\Services;

class GeolocationService
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     * 
     * @param float $lat1 Latitude 1
     * @param float $lon1 Longitude 1
     * @param float $lat2 Latitude 2
     * @param float $lon2 Longitude 2
     * @return float Distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Check if a coordinate is within a geofence radius
     * 
     * @param float $latitude Current latitude
     * @param float $longitude Current longitude
     * @param float $centerLat Center latitude
     * @param float $centerLon Center longitude
     * @param int $radiusMeter Radius in meters
     * @return array ['is_within' => bool, 'distance' => float]
     */
    public static function isWithinGeofence($latitude, $longitude, $centerLat, $centerLon, $radiusMeter)
    {
        $distance = self::calculateDistance($latitude, $longitude, $centerLat, $centerLon);
        
        return [
            'is_within' => $distance <= $radiusMeter,
            'distance' => $distance
        ];
    }

    /**
     * Format distance for display
     * 
     * @param float $meters Distance in meters
     * @return string Formatted distance
     */
    public static function formatDistance($meters)
    {
        if ($meters >= 1000) {
            return round($meters / 1000, 2) . ' km';
        }
        
        return round($meters) . ' m';
    }
}
