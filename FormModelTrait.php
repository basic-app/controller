<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Controller;

trait FormModelTrait
{

    protected function formModelErrors() : array
    {
        return $this->formModel->errors();
    }

    protected function formModelSave($data) : bool
    {
        return $this->formModel->save($data);
    }

    protected function formModelFind($id = null)
    {
        return $this->formModel->find($id);
    }

    protected function formModelFindAll(int $limit = 0, int $offset = 0)
    {
        return $this->formModel->findAll($limit, $offset);
    }

    public function formModelDelete($id = null, bool $purge = false)
    {
        return $this->formModel->delete($id, $purge);
    }

    public function formModelInsertID()
    {
        return $this->formModel->insertID();
    }

    public function formModelIdValue($data)
    {
        return $this->formModel->idValue($data);
    }

    public function formModelReturnType()
    {
        return $this->formModel->returnType;
    }

    public function formModelPrimaryKey()
    {
        return $this->formModel->primaryKey;
    }

    public function formModelAllowedFields()
    {
        return $this->formModel->allowedFields;
    }

}