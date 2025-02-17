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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;

class NoAnalyzerInsideWithProperty extends DSL {
    public function run() {
        list($atoms, $analyzer, $property, $value) = func_get_args();

        $this->assertProperty($property);
        $this->assertAtom($atoms);
        $this->assertAnalyzer($analyzer);

        $diff = $this->normalizeAtoms($atoms);

        if (empty($diff)) {
            return new Command(Query::STOP_QUERY);
        }

        $MAX_LOOPING = self::$MAX_LOOPING;
        $linksDown = self::$linksDown;

$gremlin = <<<GREMLIN
not( 
    where( __.emit( ).repeat( __.out({$linksDown}) ).times($MAX_LOOPING)
             .hasLabel(within(***))
             .filter{ it.get().value("$property") == $value}
             .where( __.in("ANALYZED").has("analyzer", within(***)))
          )     
)
GREMLIN;
        return new Command($gremlin,
                           array($diff, makeArray($analyzer)));
    }
}
?>
