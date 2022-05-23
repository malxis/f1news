<?php
class tools
{
    /**
     * Format a french date to an english date (ex: 1970-12-31)
     *
     * @param string $date Date to format
     * @return sting
     */
    function dateFrenchToEnglish($date)
    {
        return date("Y-m-d", strtotime($date));;
    }

    /**
     * Format an english date to a french date (ex: 31/12/1970)
     *
     * @param string $date Date to format
     * @return string
     */
    function dateEnglishToFrench($date)
    {
        return date("d/m/Y", strtotime($date));
    }

    /**
     * Format an english datetime to a french datetime (ex: 31/12/1970 à 12h00)
     *
     * @param [type] $myDate
     * @return void
     */
    function dateTimeEnglishToFrench($myDate)
    {
        @list($year, $month, $day) = explode('-', substr($myDate, 0, 10));
        @list($hour, $min, $sec) = explode(':', substr($myDate, 11));
        $date = $day . "/" . $month . "/" . $year . " à " . $hour . "h" . $min;
        return $date;
    }

    /**
     * Return the pagination element to display in a view
     *
     * @param integer $page Actual page
     * @param string $path Path for the previous/next page
     * @param integer $totalElementCount Number of total elements
     * @param integer $elementPerPage Number of element to display in a page
     * @return string
     */
    function pagination($page, $path, $totalElementCount, $elementPerPage = 5)
    {
        if ($totalElementCount > $elementPerPage) {
            $pagePrevious = $path . ($page - 1);
            $pageNext = $path . ($page + 1);

            $html = '<div class="row" id="pagination-btn">';
            $html .= '<div class="col d-flex justify-content-center">';
            $html .= '<nav>';
            $html .= '<ul class="pagination">';

            $html .= '<li class="page-item ';
            if ($page == 1)
                $html .= 'disabled';
            $html .= ' ">';

            $html .= '<a class="page-link" id="page-previous-big" href="' . $pagePrevious . '" tabindex="-1" ';
            if ($page == 1)
                $html .=  'aria-disabled="true"';
            $html .= '>Précédent</a>';
            $html .= '</li>';

            if ($page != 1) {
                $html .= '<li class="page-item" id="page-previous-li">';
                $html .= '<a class="page-link" id="page-previous" href="' . $pagePrevious . '">' . ($page - 1) . '</a>';
                $html .= '</li>';
            }

            $html .= '<li class="page-item active" id="page-actual-li" aria-current="page">';
            $html .= '<a class="page-link" href="">' . $page . '</a>';
            $html .= '</li>';

            if ($page * $elementPerPage < $totalElementCount) {
                $html .= '<li class="page-item" id="page-next-li">';
                $html .= '<a class="page-link" id="page-next" href="' . $pageNext . '">' . ($page + 1) . '</a>';
                $html .= '</li>';
            }

            $html .= '<li class="page-item ';
            if ($page * $elementPerPage >= $totalElementCount)
                $html .= 'disabled';
            $html .= ' ">';

            $html .= '<a class="page-link" id="page-next-big" href="' . $pageNext . '" ';
            if ($page * $elementPerPage >= $totalElementCount)
                $html .= 'aria-disabled="true"';
            $html .= '>Suivant</a>';
            $html .= '</li>';

            $html .= '</ul>';
            $html .= '</nav>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        } else {
            return "";
        }
    }
}
