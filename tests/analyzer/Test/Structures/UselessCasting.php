<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessCasting extends Analyzer {
    /* 4 methods */

    public function testStructures_UselessCasting01()  { $this->generic_test('Structures/UselessCasting.01'); }
    public function testStructures_UselessCasting02()  { $this->generic_test('Structures/UselessCasting.02'); }
    public function testStructures_UselessCasting03()  { $this->generic_test('Structures/UselessCasting.03'); }
    public function testStructures_UselessCasting04()  { $this->generic_test('Structures/UselessCasting.04'); }
}
?>