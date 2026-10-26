<?php

namespace VuFind\Search\QueryEnrichment;

use VuFind\Search\Base\Params;
use VuFindSearch\Query\QueryGroup;

class Manager {

    public function enrich(Params $params, ?string $enrichmentRequest)
    {
        if (!$enrichmentRequest) {
            return;
        }

        if ($params->getQuery() instanceof QueryGroup) {
            // already enriched, or advanced, either way don't touch it
            return;
        }

        $steps = $this->parseSteps($enrichmentRequest);
        foreach ($steps as $step) {
            $step->enrich($params);
        }

        // $query = $params->getQuery();
        // $query->setString($query->getString() . ' is great');
        // $foo = 1;
    }

    protected function parseSteps($enrichmentRequest)
    {
        $stepNames = explode(',', $enrichmentRequest);

        $steps = [];
        foreach ($stepNames as $stepName) {
            // TODO actually check this against config for matches and settings, don't just trust input
            $className = '\\VuFind\\Search\\QueryEnrichment\\Step\\' . $stepName;
            $step = new $className();
            $steps[] = $step;
        }
        return $steps;
    }

}
