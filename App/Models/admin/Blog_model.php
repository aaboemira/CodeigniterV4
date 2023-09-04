<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Blog_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function deletePost($id)
    {
        $this->db->transStart();
        $builder->where('id', $id)->delete('blog_posts');
        $builder->where('for_id', $id)->delete('blog_translations');
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
           // show_error(lang_safe('database_error'));
        } else {
            $this->db->transCommit();
        }
    }

    public function postsCount($search = null)
    {
        $builder = $this->db->table('blog_posts');
        if ($search !== null) {
            $builder->like('blog_translations.title', $search);
        }
        $builder->join('blog_translations', 'blog_translations.for_id = blog_posts.id', 'left');
        $builder->where('blog_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        return $builder->countAllResults();
    }

    public function getPosts($lang = null, $limit = null, $page = null, $search = null, $month = null)
    {
        $builder = $this->db->table('blog_posts');
        if ($search !== null) {
            $builder->where("(blog_translations.title LIKE '%$search%' OR blog_translations.description LIKE '%$search%')");
        }
        if ($month !== null) {
            $from = $month['from'];
            $to = $month['to'];
            $builder->where("time BETWEEN $from AND $to");
        }
        $builder->join('blog_translations', 'blog_translations.for_id = blog_posts.id', 'left');
        if ($lang == null) {
            $builder->where('blog_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        } else {
            $builder->where('blog_translations.abbr', $lang);
        }
        $query = $builder->select('blog_posts.id, blog_translations.title, blog_translations.description, blog_posts.url, blog_posts.time, blog_posts.image')->get($limit, $page);
        return $query->getResultArray();
    }

    public function setPost($post, $id)
    {
        $this->db->transStart();
        $is_update = false;
        if ($id > 0) {
            $is_update = true;
            $builder = $this->db->table('blog_posts');
            $builder->where('id', $id);
            if (!$builder->update(array(
                        'image' => $post['image'] != null ? $_POST['image'] : $_POST['old_image']
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
        } else {
            /*
             * Lets get what is default tranlsation number
             * in titles and convert it to url
             * We want our plaform public ulrs to be in default 
             * language that we use
             */
            $i = 0;
            foreach ($_POST['translations'] as $translation) {
                if ($translation == MY_DEFAULT_LANGUAGE_ABBR) {
                    $myTranslationNum = $i;
                }
                $i++;
            }
            $builder = $this->db->table('blog_posts');
            if (!$builder->insert(array(
                        'image' => $post['image'],
                        'time' => time()
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
            $id = $this->db->insertID();
            $builder = $this->db->table('blog_posts');
            $builder->where('id', $id);
            if (!$builder->update(array(
                        'url' => except_letters($_POST['title'][$myTranslationNum]) . '_' . $id
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
        }
        $this->setBlogTranslations($post, $id, $is_update);
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            //show_error(lang_safe('database_error'));
        } else {
            $this->db->transCommit();
        }
    }

    private function setBlogTranslations($post, $id, $is_update)
    {
        $i = 0;
        $current_trans = $this->getTranslations($id);
        foreach ($post['translations'] as $abbr) {
            $arr = array();
            $emergency_insert = false;
            if (!isset($current_trans[$abbr])) {
                $emergency_insert = true;
            }
            $post['title'][$i] = str_replace('"', "'", $post['title'][$i]);
            $arr = array(
                'title' => $post['title'][$i],
                'description' => $post['description'][$i],
                'abbr' => $abbr,
                'for_id' => $id
            );
            if ($is_update === true && $emergency_insert === false) {
                $abbr = $arr['abbr'];
                unset($arr['for_id'], $arr['abbr'], $arr['url']);
                if (!$builder->where('abbr', $abbr)->where('for_id', $id)->update('blog_translations', $arr)) {
                    ///log_message('error', print_r($builder->error(), true));
                }
            } else {
                $builder = $this->db->table('blog_translations');
                if (!$builder->insert($arr)) {
                    ///log_message('error', print_r($builder->error(), true));
                }
            }
            $i++;
        }
    }

    public function getOnePost($id)
    {
        $builder = $this->db->table('blog_posts');
        $query = $builder->where('id', $id)->get();
        if ($query->countAllResults() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function getTranslations($id)
    {
        $builder = $this->db->table('blog_translations');
        $builder->where('for_id', $id);
        $query = $builder->get();
        $arr = array();
        foreach ($query->getResultArray() as $row) {
            $arr[$row->abbr]['title'] = $row->title;
            $arr[$row->abbr]['description'] = $row->description;
        }
        return $arr;
    }

}