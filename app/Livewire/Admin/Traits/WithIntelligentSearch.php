<?php

namespace App\Livewire\Admin\Traits;

trait WithIntelligentSearch
{
    /**
     * Parse a command-like query string and apply structured filters to an Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @param array $textFields
     * @param array $allowedAttributes
     */
    protected function parseAndApplySearch($query, string $search, array $textFields, array $allowedAttributes = []): void
    {
        if (empty($search)) {
            return;
        }

        // Split query by whitespace, keeping quoted phrases intact
        $tokens = preg_split('/\s+(?=(?:[^\'"]*[\'"][^\'"]*[\'"])*[^\'"]*$)/', $search);
        $generalSearchTerms = [];

        foreach ($tokens as $token) {
            $token = trim($token, " '\"");
            if ($token === '') {
                continue;
            }

            // Detect key:value, key>value, key<value, key>=value, key<=value conditions
            if (preg_match('/^([a-zA-Z_]+)(:|<=|>=|<|>|=)(.+)$/', $token, $matches)) {
                $key = strtolower($matches[1]);
                $operator = $matches[2];
                $value = trim($matches[3], " '\"");

                // Normalize ":" to standard "=" operator
                if ($operator === ':') {
                    $operator = '=';
                }

                if (isset($allowedAttributes[$key])) {
                    $dbField = $allowedAttributes[$key];
                    
                    if (is_callable($dbField)) {
                        $dbField($query, $operator, $value);
                    } else {
                        $query->where($dbField, $operator, $value);
                    }
                    continue;
                }
            }

            $generalSearchTerms[] = $token;
        }

        // Apply general text query matching across mapped fields
        if (!empty($generalSearchTerms)) {
            $query->where(function ($q) use ($generalSearchTerms, $textFields) {
                foreach ($generalSearchTerms as $term) {
                    $q->where(function ($sub) use ($term, $textFields) {
                        foreach ($textFields as $field) {
                            if (str_contains($field, '.')) {
                                [$relation, $relField] = explode('.', $field, 2);
                                $sub->orWhereHas($relation, function ($relQuery) use ($relField, $term) {
                                    $relQuery->where($relField, 'like', '%' . $term . '%');
                                });
                            } else {
                                $sub->orWhere($field, 'like', '%' . $term . '%');
                            }
                        }
                    });
                }
            });
        }
    }
}
