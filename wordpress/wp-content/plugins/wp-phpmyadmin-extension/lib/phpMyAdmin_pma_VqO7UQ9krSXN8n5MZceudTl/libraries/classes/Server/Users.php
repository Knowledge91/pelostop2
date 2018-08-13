<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * set of common functions for sub tabs in server level `Users` page
 *
 * @package PhpMyAdmin
 */
namespace PhpMyAdmin\Server;

use PhpMyAdmin\Url;

/**
 * PhpMyAdmin\Server\Users class
 *
 * @package PhpMyAdmin
 */
class Users
{
    /**
     * Get HTML for secondary level menu tabs on 'Users' page
     *
     * @param string $selfUrl Url of the file
     *
     * @return string HTML for secondary level menu tabs on 'Users' page
     */
    public static function getHtmlForSubMenusOnUsersPage($selfUrl)
    {
        $items = array(
            array(
                'name' => __PMA_TRANSL('User accounts overview'),
                'url' => 'server_privileges.php',
                'params' => Url::getCommon(array('viewing_mode' => 'server')),
            )
        );

        if ($GLOBALS['dbi']->isSuperuser()) {
            $items[] = array(
                'name' => __PMA_TRANSL('User groups'),
                'url' => 'server_user_groups.php',
                'params' => Url::getCommon(),
            );
        }

        $retval  = '<ul id="topmenu2">';
        foreach ($items as $item) {
            $class = '';
            if ($item['url'] === $selfUrl) {
                $class = ' class="tabactive"';
            }
            $retval .= '<li>';
            $retval .= '<a' . $class;
            $retval .= ' href="' . $item['url'] . $item['params'] . '">';
            $retval .= $item['name'];
            $retval .= '</a>';
            $retval .= '</li>';
        }
        $retval .= '</ul>';
        $retval .= '<div class="clearfloat"></div>';

        return $retval;
    }
}