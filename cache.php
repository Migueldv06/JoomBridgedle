<?php
class JoomBridgedleCacheHelper
{
    public static function getCache($moduleId, $key, $expiryTime)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['data', 'cache_date']))
            ->from($db->quoteName('#__joombridgedle'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($query);
        $result_db = $db->loadResult();

        if ($result_db) {
            $cacheDate = new DateTime($result_db->cache_date);
            $expiryDate = $cacheDate->modify('+' . $expiryTime . ' minutes');
            $currentDate = JFactory::getDate()->toSql();
            if ($currentDate < $expiryDate) {
                return $result_db;
            } else {
                // remove cache expirado
                self::clearCache($moduleId, $key);
            }
        }
        return false;
    }
    public static function setCache($moduleId, $key, $data)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $existsQuery = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__joombridgedle'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($existsQuery);
        $exists = (int) $db->loadResult();

        if ($exists) {
            $query->update($db->quoteName('#__joombridgedle'))
                ->set($db->quoteName('data') . ' = ' . $db->quote($data))
                ->set($db->quoteName('cache_date') . ' = ' . $db->quote(JFactory::getDate()->toSql()))
                ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
                ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        } else {
            $columns = array('module_id', 'cache_key', 'data', 'cache_date');
            $values = array($db->quote($moduleId), $db->quote($key), $db->quote($data), $db->quote(JFactory::getDate()->toSql()));

            $query->insert($db->quoteName('#__joombridgedle'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        }
        $db->setQuery($query);
        $db->execute();
    }
    public static function clearCache($moduleId, $key)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__joombridgedle'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($query);
        $db->execute();
    }
}
