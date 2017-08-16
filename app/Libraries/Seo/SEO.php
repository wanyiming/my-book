<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 20:35
 * Author: dch
 */
namespace App\Libraries\Seo;

use App\Models\PubSeoRule;
use Exception;
use Log;
use Throwable;

class SEO
{
    public $title;
    public $keywords;
    public $description;
    protected $variables = [];//变量
    protected $callKey;

    /**
     * 设置SEO规则
     *
     * 规则来自数据表现 pub_seo_rule.call_key
     *
     * @param string $callKey
     * @return $this
     * @author dch
     */
    public function setRule(string $callKey)
    {
        $this->callKey = strtoupper($callKey);
        return $this;
    }

    /**
     * 设置 title
     *
     * @param string $title
     * @return $this
     * @author dch
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 设置 keywords
     *
     * @param array|string ...$keywords
     * @return $this
     * @author dch
     */
    public function setKeywords(...$keywords)
    {
        $this->keywords = array_reduce($keywords, [$this, 'reduce']);
        return $this;
    }

    /**
     * 设置 description
     *
     * @param string $description
     * @return $this
     * @author dch
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 调用TDK
     *
     * @return string
     * @author dch
     */
    public function generate()
    {
        $this->compile();
        return sprintf('<title>%s</title>' . PHP_EOL
            . '    <meta name="description" content="%s">' . PHP_EOL
            . '    <meta name="keywords" content="%s">',
            htmlspecialchars($this->title),
            htmlspecialchars($this->description),
            htmlspecialchars($this->keywords)
        );
    }

    /**
     * 设置变量
     *
     * @param string $variableName
     * @param string $value
     * @return $this
     * @author dch
     */
    public function setVariable(string $variableName, string $value)
    {
        $this->variables[strtolower($variableName)] = $value;
        return $this;
    }

    public function existsVariable(string $variableName)
    {
        return isset($this->variables[strtolower($variableName)]);
    }

    /**
     * 批量设置变量
     *
     * @param array $variables
     * @return $this
     * @author dch
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $variableName => $variableValue){
            $this->setVariable($variableName,$variableValue);
        }
        return $this;
    }

    /**
     * 设置关键词
     *
     * @param string $function_name
     * @param array $arguments
     * @author dch
     * @return $this
     */
    public function __call(string $function_name, array $arguments)
    {
        if ('set' === strtolower(substr($function_name, 0, 3))) {
            $this->setVariable(substr($function_name, 3),array_reduce($arguments, [$this, 'reduce']));
        }
        return $this;
    }

    protected function compile()
    {
        if (empty($this->callKey)) {
            return;
        }
        try{
            $this->initGlobalVariables();
            $seoData = PubSeoRule::where('call_key', $this->callKey)->first();
        }catch (Exception $e) {
            Log::error($e);
        }catch (Throwable $e){
            Log::error($e);
        }
        if (empty($seoData)) {
            return;
        }
        $pattens = [];
        $replacements = [];
        foreach ($this->variables as $variableName => $variableValue) {
            $pattens[] = '~\{'.$variableName.'\}~is';
            $replacements[] = $variableValue;
        }

        $pattens[] = '~\{.*?\}~is';
        $replacements[] = '';

        $this->setTitle(preg_replace($pattens, $replacements , $seoData->title));
        $this->setKeywords(preg_replace($pattens, $replacements , $seoData->keywords));
        $this->setDescription(preg_replace($pattens, $replacements , $seoData->description));
        return;
    }

    protected function initGlobalVariables()
    {
        $results  = \DB::table('sys_variable')->get();
        foreach ($results as $result) {
            if(!$this->existsVariable($result->variable)){
                $this->setVariable($result->variable,$result->name);
            }
        }
        return ;
    }
    protected function reduce($ret, $next)
    {
        if (is_array($next)) {
            $next = implode(',', $next);
        }
        return empty($ret) ? $next : implode(',', [$ret, $next]);
    }
}