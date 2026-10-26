<?php

namespace VuFind\Search\QueryEnrichment\Step;

use VuFind\Search\Base\Params;
use VuFindSearch\Query\Query;
use VuFindSearch\Query\QueryGroup;

class DemoSynonyms implements StepInterface {

    protected array $synonyms = [
        'a' => ['aardvark', 'appendix', 'aggravation', 'audible', 'Afghanistan'],
        'b' => ['balloon', 'banquet', 'burp', 'besmirch', 'bidet'],
        'c' => ['callous', 'coriander', 'Cupid', 'cinderblock', 'cesium'],
        'd' => ['doggerel', 'didgeridoo', 'diagram', 'dunderhead', 'Delaware'],
        'e' => ['exegesis', 'ether', 'edification', 'escape', 'equal'],
        'f' => ['Fahrenheit', 'flexible', 'funny', 'fixture', 'ferrous'],
        'g' => ['gestalt', 'goaltender', 'garrulous', 'gunk', 'gimlet'],
        'h' => ['hilarious', 'henna', 'halitosis', 'husband', 'hoarding'],
        'i' => ['igloo', 'interesting', 'index', 'ineffective', 'island'],
        'j' => ['jackal', 'jovial', 'juvenile', 'Jesuit', 'jinx'],
        'k' => ['kraken', 'Klondike', 'keeper', 'kindergarten', 'Korea'],
        'l' => ['lethargy', 'lava', 'luxurious', 'licensure', 'localization'],
        'm' => ['metallurgy', 'mathematics', 'monster', 'midden', 'mustard'],
        'n' => ['nutella', 'northern', 'nibble', 'nation', 'Neverland'],
        'o' => ['opera', 'oblong', 'odyssey', 'orientation', 'operator'],
        'p' => ['pentagon', 'parade', 'potion', 'puerile', 'piston'],
        'q' => ['queen', 'quotient', 'quarrel', 'quicken', 'Qatar'],
        'r' => ['Romulan', 'rigatoni', 'radiation', 'restitution', 'rustic'],
        's' => ['salmonella', 'seismic', 'simulation', 'solution', 'suspicion'],
        't' => ['turnstile', 'tendon', 'taxi', 'tickle', 'torpor'],
        'u' => ['upsilon', 'underhand', 'uvula', 'ulterior', 'Uganda'],
        'v' => ['version', 'vacillate', 'villain', 'volume', 'vulture'],
        'w' => ['wonderful', 'wax', 'Western', 'window', 'Wyoming'],
        'x' => ['x-ray', 'X-Men'],
        'y' => ['yellow', 'youth', 'yip', 'York', 'yap'],
        'z' => ['zygote', 'zealous', 'zipper', 'zoo', 'zero'],
    ];

    public function enrich(Params $params)
    {
        $initialQuery = $params->getQuery();
        if ($initialQuery instanceof QueryGroup) {
            // noop
            return;
        }

        $query = new QueryGroup('AND');
        $initialTerms = explode(' ', $initialQuery->getString());
        $handler = $initialQuery->getHandler();
        foreach($initialTerms as $term) {
            $termGroup = new QueryGroup('OR');
            $synonyms = $this->addSynonyms($term);
            foreach ($synonyms as $synonym) {
                $termGroup->addQuery(new Query($synonym, $handler));
            }
            $query->addQuery($termGroup);
        }
        $params->setQuery($query);
    }

    protected function addSynonyms(string $term): array {
        $firstLetter = strtolower(substr($term, 0, 1));
        $synonyms = $this->synonyms[$firstLetter];
        shuffle($synonyms);
        return array_merge([$term], array_slice($synonyms, 0, 2));
    }

}
