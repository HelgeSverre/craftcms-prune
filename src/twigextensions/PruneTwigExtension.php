<?php

namespace HelgeSverre\Prune\twigextensions;

use Craft;
use yii\base\Model;


/**
 * Class PruneTwigExtension
 * @package HelgeSverre\Prune\twigextensions
 */
class PruneTwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var array
     */
    protected $input = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * Get name of the Twig extension
     *
     * @return string
     */
    public function getName()
    {
        return 'Prune';
    }

    /**
     * Get a list of the Twig filters this extension is providing
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter("prune", [$this, 'prune']),
        ];
    }

    /**
     * Convert a BaseModel into an array with the specified fields
     *
     * @param array $input The content being filtered
     * @param array $fields An array of the fields to keep
     * @return array
     * @throws \Exception
     */
    public function prune(array $input, array $fields)
    {
        if (!is_array($fields)) {
            throw new \Exception(Craft::t('Map parameter needs to be an array.'));
        }

        if (!is_array($input)) {
            throw new \Exception(Craft::t('Content passed is not an array.'));
        }

        $this->input = $input;
        $this->fields = $fields;

        $output = [];

        foreach ($input as $element) {
            if (!($element instanceof Model)) {
                continue;
            }

            $output[] = $this->returnPrunedArray($element);
        }

        return $output;
    }

    /**
     * Given a BaseModel, return an array with only requested fields
     *
     * @param Model $item
     * @return array
     */
    protected function returnPrunedArray(Model $item)
    {
        $new_item = [];

        foreach ($this->fields as $key) {
            if (isset($item->{$key})) {
                if (is_object($item->{$key}) && method_exists($item->{$key}, 'attributeNames')) {
                    $new_item[$key] = new \stdClass();
                    foreach ($item->{$key}->attributeNames() as $attribute) {
                        $new_item[$key]->$attribute = $item->{$key}->{$attribute};
                    }
                } else {
                    $new_item[$key] = $item->{$key};
                }
            }
        }

        return $new_item;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [];
    }
}
