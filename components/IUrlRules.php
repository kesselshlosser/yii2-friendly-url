<?php
/**
 * Created by PhpStorm.
 * User: max
 * Name: Cherednyk Maxim
 * Phone: +380639960375
 * Email: maks757q@gmail.com
 * Date: 30.05.17
 * Time: 11:37
 */

namespace maks757\friendly\components;


interface IUrlRules
{
    /**
     * @param mixed $key
     * @return integer model id
     */
    function fiendKey($key);


    /**
     * @param integer $id
     * @return string
     */
    function getSeoUrl($id);
}