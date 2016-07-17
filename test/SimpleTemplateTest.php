<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

class SimpleTemplateTest extends PHPUnit_Framework_TestCase {

    private $testContent = '&lt;script&gt;alert(&quot;hello world&quot;);&lt;/script&gt;(12)&lt;p&gt;What?&lt;/p&gt;(23)';

    public function testNew() {
        return new SimpleTemplate(__DIR__ . '/test.tpl');
    }

    /**
     * @depends testNew
     */
    public function testAssign($tpl) {
        $tpl->assign('data', [
            [
                'name' => '<script>alert("hello world");</script>',
                'age'  => 12,
            ],
            [
                'name' => '<p>What?</p>',
                'age'  => 23,
            ],
        ]);
        return $tpl;
    }

    /**
     * @depends testAssign
     */
    public function testRenderContent($tpl) {
        $content = $tpl->render(false);
        $this->assertEquals($content, $this->testContent);
    }

    /**
     * @depends testAssign
     */
    public function testRender($tpl) {
        $content = $tpl->render();
        $this->expectOutputString($this->testContent);
    }

}