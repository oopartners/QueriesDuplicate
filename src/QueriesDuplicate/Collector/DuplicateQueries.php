<?php
namespace QueriesDuplicate\Collector;

use ZendDeveloperTools\Collector;

use Zend\Mvc\MvcEvent;

/**
 * Collector to be used in ZendDeveloperTools to record and display ik duplicate queries
 *
 * @author  Guillaume Beausse
 */
class DuplicateQueries extends \ZendDeveloperTools\Collector\AbstractCollector
{
    private static $queries = array();

    private static $total_sql_time = 0;
    private static $total_queries  = 0;

    /**
     * Construct.
     *
     */
    public function __construct()
    {

    }

    static public function saveQuery($query,$time)
    {
        //Split queries to manage huge queries
        self::$queries[substr($query,0,5000)]['nb'] += 1;
        self::$queries[substr($query,0,5000)]['duration'] += $time;
        self::$total_queries++;
        self::$total_sql_time+=$time;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'duplicate-queries-configs';
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(MvcEvent $mvcEvent)
    {
    }

    /**
     *
     *
     * @return string
     */
    public function getTotalQueries()
    {
        return self::$total_queries;
    }
    /**
     *
     *
     * @return string
     */
    public function getTotalExecutionTime()
    {
        return round(self::$total_sql_time,6);
    }

    /**
     *
     *
     * @return string
     */
    public function getQueryDetail()
    {
        $html = '';
        $total = array();

        if(!empty(self::$queries))
        {
            $html = '<table style="width:100%">';

            foreach (self::$queries as $query => $data) {
                $total[$query]     = $data['nb'];
                $duration[$query]  = $data['duration'];
            }

            array_multisort($total, SORT_DESC, $duration, SORT_DESC,self::$queries);

            $html .= '<tr style="border-bottom: 1px solid #FEFEFE">
                           <td style="padding:2px;border-right: 1px solid #FEFEFE">Nb</td>
                           <td style="text-align:center;padding:2px;border-right: 1px solid #FEFEFE">Time</td>
                           <td style="text-align:center;padding:2px;border-right: 1px solid #FEFEFE">Cumul</td>
                           <td style="padding:2px;border-right: 1px solid #FEFEFE">Query</td>
                         </tr>';
            $duration_cumul = 0;
            $i = 0;

            foreach(self::$queries as $query => $data)
            {
                $i++;
                $duration_cumul += $data['duration'];

                $html .= '<tr style="border-bottom: 1px dotted #FEFEFE">
                           <td style="padding:2px;border-right: 1px dotted #FEFEFE">'.$data['nb'].'</td>
                           <td style="text-align:center;padding:2px;border-right: 1px dotted #FEFEFE">'.round($data['duration'],6).'</td>
                           <td style="text-align:center;padding:2px;border-right: 1px dotted #FEFEFE">'.round($duration_cumul,6).'</td>
                           <td style="padding:2px;border-right: 1px dotted #FEFEFE">'.$query.'</td>
                         </tr>';

                if($i == 500)
                {
                    $html .= '
                        <tr style="border-bottom: 1px dotted #FEFEFE">
                           <td colspan="4" style="text-align:center"> ('.(count(self::$queries)-500).' lines not displayed)</td>
                         </tr>';
                    break;
                }
            }

            $html .= '</table>';
        }
        return $html;
    }
}