<?php


class Pagination
{
    const DEFAULT_RANGE = 2;
    const DEFAULT_PREFIX = '/';
    const DEFAULT_SUFIX = '';
    const DEFAULT_PARAMETER = '';

    private $totalItems;
    private $itemsPerPage;
    private $recountPages;
    private $totalPages;
    private $currentPage;
    private $range;
    private $url;
    private $prefix = '/';
    private $sufix = '';
    private $parameter = '';

    public function __construct($params)
    {
        if(is_array($params)) {
            if(isset($params['totalItems'])&&is_int($params['totalItems'])) {
                $this->totalItems = $params['totalItems'];
            }
            if(isset($params['itemsPerPage'])&&is_int($params['itemsPerPage'])) {
                $this->itemsPerPage = $params['itemsPerPage'];
            }
            if(isset($params['totalPages'])&&is_int($params['totalPages'])) {
                $this->totalPages = $params['totalPages'];
            }
            if(isset($params['currentPage'])&&is_int($params['currentPage'])) {
                $this->currentPage = $params['currentPage'];
            }
            if(isset($params['url'])&&is_string($params['url'])) {
                $this->url = $params['url'];
            }
            $this->range = isset($params['range'])?$params['range']:self::DEFAULT_RANGE;
            $this->prefix = isset($params['prefix'])?$params['prefix']:self::DEFAULT_PREFIX;
            $this->sufix = isset($params['sufix'])?$params['sufix']:self::DEFAULT_SUFIX;
            $this->parameter = isset($params['parameter'])?$params['parameter']:self::DEFAULT_PARAMETER;

            $this->recountPages = isset($params['recountPages'])?$params['recountPages']:false;
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'totalItems':
                if(is_integer($value)) {
                    $this->totalItems = $value;
                }
                break;
            case 'itemsPerPage':
                if(is_integer($value)) {
                    $this->itemsPerPage = $value;
                }
                break;
            case 'recountPages':
                if(is_bool($value)) {
                    $this->recountPages = $value;
                }
                break;
            case 'totalPages':
                if(is_integer($value)) {
                    $this->totalPages = $value;
                }
                break;
            case 'currentPage':
                if(is_integer($value)) {
                    $this->currentPage = $value;
                }
                break;
            case 'url':
                if(is_string($value)) {
                    $this->url = $value;
                }
                break;
            case 'range':
                if(is_integer($value)) {
                    $this->range = $value;
                }
                break;
            case 'prefix':
                if(is_string($value)) {
                    $this->prefix = $value;
                }
                break;
            case 'sufix':
                if(is_string($value)) {
                    $this->sufix = $value;
                }
                break;
            case 'parameter':
                if(is_string($value)) {
                    $this->parameter = $value;
                }
                break;
            default:
                break;
        }
    }

    public function countTotalPages($totalItems, $itemsPerPage) {
        return intval(floor($totalItems/$itemsPerPage)+($totalItems%$itemsPerPage>0?1:0));
    }

    public function render() {
        if(isset($this->totalItems)&&isset($this->itemsPerPage)) {
            if((!isset($this->totalPages))||$this->recountPages) {
                $this->totalPages = $this->countTotalPages($this->totalItems, $this->itemsPerPage);
            }
        }
        if(!(isset($this->totalPages)&&isset($this->currentPage)&&isset($this->url))) {
            return false;
        }
        ?>
        <ul class="pagination">
            <?php
            if($this->currentPage>$this->range+1){
                ?>
                <li><a href="<?=$this->url.$this->prefix.$this->parameter.'1'.$this->sufix?>">&lt;&lt;</a></li>
                <?php
            }
            if($this->currentPage>1) {
                ?>
                <li><a href="<?=$this->url.$this->prefix.$this->parameter.''.($this->currentPage-1).$this->sufix?>">&lt;</a></li>
                <?php
            }
            for($i=$this->currentPage-$this->range;$i>=$this->currentPage-$this->range&&$i<=$this->currentPage+$this->range;$i++) {
                if($i>=1&&$i<=$this->totalPages) {
                    if($i==$this->currentPage) {
                        ?>
                        <li><span class="active"><?=$this->currentPage?></span></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="<?= $this->url . $this->prefix . $this->parameter . '' . $i.$this->sufix ?>"><?= $i ?></a></li>
                        <?php
                    }
                }
            }
            if($this->currentPage<$this->totalPages) {
                ?>
                <li><a href="<?=$this->url.$this->prefix.$this->parameter.''.($this->currentPage+1).$this->sufix?>">&gt;</a></li>
                <?php
            }
            if($this->currentPage<$this->totalPages-2) {
                ?>
                <li><a href="<?=$this->url.$this->prefix.$this->parameter.''.$this->totalPages.$this->sufix?>">&gt;&gt;</a></li>
                <?php
            }
            ?>
        </ul>
        <?php
        return true;
    }


}