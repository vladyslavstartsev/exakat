<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class InsufficientTypehint extends Analyzer {
    public function analyze() {
        // function foo(i $a) { $i->a(); } // but interface i has no function a()
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->_as('arg')
             ->hasOut('TYPEHINT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'call')
             ->back('arg')
             ->outIs('TYPEHINT')
             ->inIs('DEFINITION')
             ->not(
                $this->side()
                     ->goToAllImplements(self::INCLUDE_SELF)
                     ->outIs(array('METHOD', 'MAGICMETHOD'))
                     ->outIs('NAME')
                     ->samePropertyAs('lccode', 'call', self::CASE_INSENSITIVE)
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
