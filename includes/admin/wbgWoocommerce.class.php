<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgWoocommerce
{
    /**
     * @param int $limit
     * @return array
     */
    public static function getProducts($limit = -1)
    {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => (int)$limit,
            'status' => 'publish'
        );


        $collection = array();
        foreach (get_posts($args) as $post) {
            $collection[] = new WC_Product($post->ID);
        }
        return $collection;
    }


    /**
     * @param int $limit
     * @return array
     */
    public static function getRandomProducts($limit = -1)
    {

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => (int)$limit,
            'status' => 'publish',
            'order' => 'random'
        );


        $collection = array();
        foreach (get_posts($args) as $post) {
            $collection[] = new WC_Product($post->ID);
        }
        return $collection;
    }

    /**
     * @param $ids
     * @return array
     */
    public static function getProductsByIDs($ids)
    {
        if (!$ids || !is_array($ids) && empty($ids)) {
            return array();
        }
        $collection = array();
        foreach ($ids as $id){
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'status' => 'publish',
                'post__in' => array($id),
                'orderby' => 'post__in'
            );
            $post = get_posts($args);
            if (!isset($post[0])){
                continue;
            }
            $post = $post[0];
            $collection[] = new WC_Product($post->ID);
        }
        return $collection;
    }


    /**
     * @param WC_Product $product
     * @param $label
     * @return mixed
     */
    public static function prepareProductLabel(WC_Product $product, $label)
    {
        $label = str_replace('%name%', $product->get_title(), $label);
        $label = str_replace('%price%', $product->get_price(), $label);
        $label = str_replace('%sale_price%', $product->get_sale_price(), $label);
        $label = str_replace('%regular_price%', $product->get_regular_price(), $label);
        $label = str_replace('%price_suffix%', $product->get_price_suffix(), $label);
        $label = str_replace('%currency%', get_woocommerce_currency_symbol(), $label);

        return $label;
    }

}