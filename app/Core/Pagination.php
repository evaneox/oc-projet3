<?php


namespace App\Core;


class Pagination {

    private $totalItems;
    private $currentPage;
    private $numPages;
    private $itemsPerPage;
    private $maxPagesToShow;

    /**
     * Construction
     *
     * @param null $currentPage
     * @param null $totalItems
     */
    public function __construct($currentPage = null, $totalItems = null)
    {
        $this->currentPage      = $currentPage;
        $this->totalItems       = $totalItems;
        $this->itemsPerPage     = PAG_ITEM_PER_PAGE;
        $this->maxPagesToShow   = PAG_MAX_PAGE;
        $this->setNumPages();
    }

    /**
     * Récupération de la page courante
     *
     * @return int
     */
    public function getCurrentPage(){
        return (int) $this->currentPage;
    }

    /**
     * Mise à jour de la page courante
     *
     * @param $currentPage
     */
    public function setCurrentPage($currentPage){
        $this->currentPage  = (int) $currentPage;
    }

    /**
     * Récupération du nombre d'éléments total
     *
     * @return int
     */
    public function getTotalItems(){
        return (int) $this->total;
    }

    /**
     * Mise à jour du nombre total d'éléments
     *
     * @param $totalItems
     */
    public function setTotalItems($totalItems){
        $this->totalItems  = (int) $totalItems;
    }

    /**
     * Récupération du nombre de page
     *
     * @return int
     */
    public function getNumPages(){
        return (int) $this->numPages;
    }

    /**
     * Calcul du nombre de pages pour la pagination
     */
    private function setNumPages(){

        $this->numPages = ($this->itemsPerPage == 0 ? 0 : (int) ceil($this->totalItems/$this->itemsPerPage));
    }

    /**
     * Retourne le numéro de la page suivante
     *
     * @return null
     */
    public function getNextPage()
    {
        if ($this->currentPage < $this->numPages) {
            return $this->currentPage + 1;
        }
        return null;
    }

    /**
     * Retourne le numéro de la page précédente
     *
     * @return null
     */
    public function getPrevPage()
    {
        if ($this->currentPage > 1) {
            return $this->currentPage - 1;
        }
        return null;
    }

    /**
     * Génére l'URL associé à une page
     *
     * @param $pageNum
     * @return string
     */
    public function getPageUrl($pageNum)
    {
        return '?' . PAG_KEY . '=' . $pageNum;
    }

    /**
     * Génére l'URL associé à la page suivante
     *
     * @return null|string
     */
    public function getNextUrl()
    {
        if (!$this->getNextPage()) {
            return null;
        }
        return $this->getPageUrl($this->getNextPage());
    }

    /**
     * Génére l'URL associé à la page précédente
     *
     * @return null|string
     */
    public function getPrevUrl()
    {
        if (!$this->getPrevPage()) {
            return null;
        }
        return $this->getPageUrl($this->getPrevPage());
    }


    /**
     * Génére la structure pour la déclaration d'une nouvelle page pour la pagination
     * Celle-ci génére aussi une page vide dans le cas ou on dépasse le nombre de page autorisé
     *
     * @param null $pageNum
     * @param bool $isCurrent
     * @return array
     */
    protected function createPage($pageNum = null, $isCurrent = false)
    {
        return array(
            'num' => !empty($pageNum) ? $pageNum : '...' ,
            'url' => !empty($pageNum) ? $this->getPageUrl($pageNum) : null,
            'isCurrent' => $isCurrent,
        );
    }

    /**
     * Construction d'un tableau contenant les données associé aux pages
     *
     * @return array
     */
    public function getPages(){

        $pages = array();

        // 1. Si une seule page
        //    On n'affiche pas de pagination
        if ($this->numPages <= 1) {
            return array();
        }
        // 2. Si le total de page n'éxcéde pas le nombre autorisé
        //    On peut afficher l'ensemble des pages
        if ($this->numPages <= $this->maxPagesToShow) {
            for ($i = 1; $i <= $this->numPages; $i++) {
                $pages[] = $this->createPage($i, $i == $this->currentPage);
            }
        }
        // 3. Dans le cas contraire
        //    On n'affiche que les 3 pages adjacentes maximum à celle courante
        //    et on va insérer entre les deux une page vide contenant [...]
        else {
            // On détermine le début et la fin de la tranche
            // de page qui seront affichés sans depasser les limites des pages.
            $numAdjacents = (int) floor(($this->maxPagesToShow - 3) / 2);

            if ($this->currentPage + $numAdjacents > $this->numPages) {
                $startRange = $this->numPages - $this->maxPagesToShow + 2;
            } else {
                $startRange = $this->currentPage - $numAdjacents;
            }
            if ($startRange < 2) $startRange = 2;

            $endRange = $startRange + $this->maxPagesToShow - 3;

            if ($endRange >= $this->numPages) $endRange = $this->numPages - 1;

            // Maintenant que nous avons déterminer la portion de page
            // à afficher nous pouvons commmencer à construire le tableau de données.
            // 1. On enregistre la première page
            // 2. On enregistre les page de la tranche
            // 3. On enregistre la dernière page
            // 4. Au besoin on joute des page vide
            $pages[] = $this->createPage(1, $this->currentPage == 1);

            if ($startRange > 2) {
                $pages[] = $this->createPage();
            }

            for ($i = $startRange; $i <= $endRange; $i++) {
                $pages[] = $this->createPage($i, $i == $this->currentPage);
            }

            if ($endRange < $this->numPages - 1) {
                $pages[] = $this->createPage();
            }

            // On enregistre la dernière page
            $pages[] = $this->createPage($this->numPages, $this->currentPage == $this->numPages);
        }
        return $pages;
    }





}