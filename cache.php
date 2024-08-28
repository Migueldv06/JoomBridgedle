<?php
class JoomBridgedleCacheHelper
{
    // Função para buscar dados do cache
    public static function getCache($moduleId, $key)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['data', 'expiry_date']))
            ->from($db->quoteName('#__joombridget_cache'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($query);
        $result = $db->loadObject();

        if ($result) {
            $currentDate = JFactory::getDate()->toSql();
            // Verifica se o cache expirou
            if ($result->expiry_date > $currentDate) {
                return json_decode($result->data, true);
            } else {
                // Se expirou, remove o cache
                self::clearCache($moduleId, $key);
            }
        }

        return false;
    }

    // Função para salvar dados no cache com expiração
    public static function setCache($moduleId, $key, $data, $expiryTimeInMinutes = 60)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $expiryDate = JFactory::getDate('+' . $expiryTimeInMinutes . ' minutes')->toSql();

        // Verifica se o cache já existe
        $existsQuery = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__joombridget_cache'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($existsQuery);
        $exists = (int) $db->loadResult();

        if ($exists) {
            // Atualiza o cache existente
            $query->update($db->quoteName('#__joombridget_cache'))
                ->set($db->quoteName('data') . ' = ' . $db->quote(json_encode($data)))
                ->set($db->quoteName('created_at') . ' = ' . $db->quote(JFactory::getDate()->toSql()))
                ->set($db->quoteName('expiry_date') . ' = ' . $db->quote($expiryDate))
                ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
                ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        } else {
            // Insere um novo cache
            $columns = array('module_id', 'cache_key', 'data', 'created_at', 'expiry_date');
            $values = array($db->quote($moduleId), $db->quote($key), $db->quote(json_encode($data)), $db->quote(JFactory::getDate()->toSql()), $db->quote($expiryDate));

            $query->insert($db->quoteName('#__joombridget_cache'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        }

        $db->setQuery($query);
        $db->execute();
    }

    // Função para limpar o cache
    public static function clearCache($moduleId, $key)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__joombridget_cache'))
            ->where($db->quoteName('module_id') . ' = ' . $db->quote($moduleId))
            ->where($db->quoteName('cache_key') . ' = ' . $db->quote($key));
        $db->setQuery($query);
        $db->execute();
    }
}
