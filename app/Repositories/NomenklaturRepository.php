<?php

namespace App\Repositories;

use App\Models\Bidang;
use App\Models\Nomenklatur;
use Collective\Html\FormFacade;
use Illuminate\Support\Facades\Auth;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class NomenklaturRepository
 * @package App\Repositories
 * @version November 9, 2023, 8:53 am UTC
 *
 * @method Nomenklatur findWithoutFail($id, $columns = ['*'])
 * @method Nomenklatur find($id, $columns = ['*'])
 * @method Nomenklatur first($columns = ['*'])
*/
class NomenklaturRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'nama',
        'description',
        'id_parent',
        'level',
        'id_increament',
        'id_satuan',
        'multi_select',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Nomenklatur::class;
    }

    public function get_array_year(){
        $collection = Nomenklatur::whereNull('id_parent')->orderBy('kode', 'asc')->get();
        $array = ['0' => 'Pilih Nomenklatur'];

        foreach($collection as $item){
            $array[$item['id']] = $item['kode'];
        }

        return $array;
    }

    public function get_array_bidang_all($parent = null){
        if(empty($parent)) $collection = Bidang::with('childs')->whereNull('id_parent')->orderBy('id_increament', 'asc')->get();
        else $collection = Bidang::with('childs')->where('id_parent', $parent)->orderBy('id_increament', 'asc')->get();

        $array = [];
        foreach($collection as $item){
            $array[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,	
                'description' => $item->description,
                'tahun_nomenklatur' => $item->tahun_nomenklatur,
                'level' => $item->level,
                'id_parent' => $item->id_parent,
                'id_satuan' => $item->id_satuan,
                'multi_select' => $item->multi_select
            ];

            if(count($item->childs))
            {
                $array = array_merge($array, $this->get_array_bidang_all($item['id']));
            }
        } 

        return $array;
    }

    public function show_nomenclatur_html($tahun){
        $user = Auth::user();
        $collection = Bidang::with('childs')->whereNull('id_parent')->where('tahun_nomenklatur', 'like', '%'.$tahun.'%')->orderBy('id_increament', 'asc')->get();

        $html = '<ul id="myUL">';
        foreach($collection as $res){
            $carret = 'caret caret-down';
            $html .= '<li>';
            
            if($res->type == 'bidang') $html .= '<span class="'.$carret.' d-block">'.$res->nama;
            else $html .= '<span class="'.$carret.' d-block"><b> '.$res->nama . '</b>';
            $html .= '</span>';
            if(count($res->childs)){
                $html .= $this->show_nomenclatur_child_html($res->id, $tahun);
            }      
            $html .= '</li>';     
        }
        $html .= '</ul>';  
        return $html;
    }
    
    public function show_nomenclatur_child_html($id, $tahun){
        $collection = Bidang::with('childs')->where('id_parent', $id)->where('tahun_nomenklatur', 'like', '%'.$tahun.'%')->orderBy('id_increament', 'asc')->get();
        $user = Auth::user();
        $html = '<ul class="nested active">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';

            $html .= '<li id="table-form-'.$res->id.'">';
            // $html .= FormFacade::open(['route' => ['nomenklaturs.destroysektor', [$tahun,$res->id]], 'method' => 'post', 'id' => 'table-form-'.$res->id]);
            $html .= '<span class="'.$carret.' d-block">'.$res->kode.'. '.$res->nama;
            
            $html .= '<div class="btn-group mg-l-10">
                <a href="'.route('nomenklaturs.edit', [$tahun,$res->id]).'" class="btn btn-outline-primary btn-xs btn-icon">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="'.route('nomenklaturs.createsektor', [$tahun,$res->id]).'" class="btn btn-outline-success btn-xs btn-icon">
                    <i class="fa fa-plus"></i>
                </a>';

            if($user->can('nomenklatur-delete')){
                $html .= FormFacade::button('<i class="fa fa-trash"></i>', [
                    'type' => 'button',
                    'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
                    'data-link' => route('nomenklaturs.destroysektor', [$tahun,$res->id]),
                    'data-id' => $res->id,
                    'data-tahun' => $tahun,
                ]);
            }
            $html .= '</div>';
            $html .= '</span>';
            if(count($res->childs)){
                $html .= $this->show_nomenclatur_child_html($res->id, $tahun);
            }          
            // $html .= FormFacade::close();   
            $html .= '</li>';     
        }
        $html .= '</ul>'; 
        return $html;
    }

    public function show_tree_html($id, $bidang){
        $collection = Bidang::with('childs')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $html = '<ul id="myUL">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';
            $html .= '<li>';
            $html .= '<span class="'.$carret.' d-block">
                    '.$res->kode.'. '.$res->nama;
            if($res->id == $bidang->id_parent){
                $html .= '<div class="btn-group mg-l-10">
                    <a class="btn btn-secondary disabled btn-xs btn-icon">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';
            }
            else{
                $html .= '<div class="btn-group mg-l-10">
                    <a href="#" class="btn btn-primary btn-xs btn-icon choose-parent" data-id="'.$res->id.'" data-kode="'.$res->kode.'" data-nama="'.$res->nama.'">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';                    
                if(count($res->childs)){
                    $html .= $this->show_tree_child_html($res->id, $bidang);
                }      
            }             
            $html .= '</li>';     
        }
        $html .= '</ul>';  
        return $html;
    }
    
    public function show_tree_child_html($id, $bidang){
        $collection = Bidang::with('childs')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $html = '<ul class="nested active">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';
            $html .= '<li>';
            $html .= '<span class="'.$carret.' d-block">
                    '.$res->kode.'. '.$res->nama;
            if($res->id == $bidang->id_parent){
                $html .= '<div class="btn-group mg-l-10">
                    <a class="btn btn-secondary disabled btn-xs btn-icon">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';             
                if(count($res->childs)){
                    $html .= $this->show_tree_child_disabled_html($res->id);
                }      
            }
            else{
                $html .= '<div class="btn-group mg-l-10">
                    <a href="#" class="btn btn-primary btn-xs btn-icon choose-parent" data-id="'.$res->id.'" data-kode="'.$res->kode.'" data-nama="'.$res->nama.'">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';                    
                if(count($res->childs)){
                    $html .= $this->show_tree_child_html($res->id, $bidang);
                }      
            }       
            $html .= '</li>';     
        }
        $html .= '</ul>'; 
        return $html;
    }

    public function show_tree_child_disabled_html($id){
        $collection = Bidang::with('childs')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $html = '<ul class="nested active">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';
            $html .= '<li>';
            $html .= '<span class="'.$carret.' d-block">
                    '.$res->kode.'. '.$res->nama;
            $html .= '</span>'; 
            if(count($res->childs)){
                $html .= $this->show_tree_child_disabled_html($res->id);
            }  
            $html .= '</li>';     
        }
        $html .= '</ul>'; 
        return $html;
    }

    public function show_sektor_html(){
        $collection = Bidang::with('childs')->whereNull('id_parent')->orderBy('id_increament', 'asc')->get();
        $html = '<ul id="myUL">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';
            $html .= '<li>';
            $html .= '<span class="'.$carret.' d-block">
                    '.$res->kode.'. '.$res->nama;
                    
            $html .= '<div class="btn-group mg-l-10">
                    <a href="#" class="btn btn-primary btn-xs btn-icon choose-parent" data-id="'.$res->id.'" data-kode="'.$res->kode.'" data-nama="'.$res->nama.'">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';                    
                if(count($res->childs)){
                    $html .= $this->show_sektor_child_html($res->id);
                }            
            $html .= '</li>';     
        }
        $html .= '</ul>';  
        return $html;
    }
    
    public function show_sektor_child_html($id){
        $collection = Bidang::with('childs')->where('id_parent', $id)->orderBy('id_increament', 'asc')->get();
        $html = '<ul class="nested active">';
        foreach($collection as $res){
            if(count($res->childs)) $carret = 'caret caret-down';
            else $carret = 'caret';
            $html .= '<li>';
            $html .= '<span class="'.$carret.' d-block">
                    '.$res->kode.'. '.$res->nama;
            
            $html .= '<div class="btn-group mg-l-10">
                    <a href="#" class="btn btn-primary btn-xs btn-icon choose-parent" data-id="'.$res->id.'" data-kode="'.$res->kode.'" data-nama="'.$res->nama.'">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                </span>';                    
            if(count($res->childs)){
                $html .= $this->show_sektor_child_html($res->id);
            }         
            $html .= '</li>';     
        }
        $html .= '</ul>'; 
        return $html;
    }
}
