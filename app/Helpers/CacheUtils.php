<?php
namespace App\Helpers;

class CacheUtils
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * get user settings from cache
     * @return {object} user settings
     */
    public function getUserSettings()
    {
        $settings = $this->getUserSettingsCache();
        return $settings;
    }

    /**
     * update user settings cache
     * @param {array} $data : array of { field => value } pair to update
     */
    public function updateUserSettingsCache($data)
    {
        $cacheData = $this->getUserSettingsCache();
        if ($cacheData) {
            foreach ($data as $key => $val) {
                $cacheData->{$key} = $val;
            }
        } else {
            $cacheData = $data;
        }
        $cacheKey = $this->getUserCacheKey();
        cache([$cacheKey => json_encode($cacheData)], 3600);
    }

    /**
     * clear user cache
     */
    public function clearUserCache()
    {
        $cacheKey = $this->getUserCacheKey();
        \Cache::forget($cacheKey);
    }

    private function getUserCacheKey()
    {
        return 'user-settings-' . $this->userId;
    }

    /**
     * get the user settings from cache | optionally, only for the provided  key
     * @param {string} $key : if a specific setting is desired, specify it's key
     */
    private function getUserSettingsCache($key = null)
    {
        $cacheKey = $this->getUserCacheKey();
        return $this->getSettingsCache($cacheKey, $key);
    }

    /**
     * get the settings from cache for the provided $cacheKey | optionally, only for the provided  key
     * @param {string} $cacheKey : cache key to get
     * @param {string} $key : if a specific setting is desired, specify it's key
     */
    private function getSettingsCache($cacheKey, $key = null)
    {
        $settingsCache = cache($cacheKey);
        if (!$settingsCache) {
            return null;
        }
        $settings = json_decode($settingsCache);
        if ($key && $settings && $settings->{$key}) {
            return $settings->{$key};
        } else if ($settings && $key === null) {
            return $settings;
        } else {
            return null;
        }
    }
}
