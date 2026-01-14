<?php
$data = json_decode(file_get_contents('data\students.json'), true);
class StudentManager
{
    private $studentData;
    function __construct($data)
    {
        $this->studentData = $data;
    }
    public function getAllstudents()
    {
        return $this->studentData;
    }
    public function getStudentById($id)
    {
        foreach ($this->studentData as $singleStudent) {
            if ($singleStudent['id'] == $id) {
                return $singleStudent;
            }
        }
        return null;
    }
    public function create($data)
    {
        $data["id"] = uniqid();
        $this->studentData[] = $data;
        $this->saveData();
        return $data["id"];
    }
    public function update($id, $data)
    {
        foreach ($this->studentData as $key => $student) {
            if ($student['id'] == $id) {
                $data['id'] = $id;
                $this->studentData[$key] = $data;
                $this->saveData();
                return true;
            }
        }
        return false;
    }
    public function delete($id)
    {
        foreach ($this->studentData as $key => $student) {
            if ($student['id'] == $id) {
                unset($this->studentData[$key]);
                $this->studentData = array_values($this->studentData);
                $this->saveData();
                return true;
            }
        }
        return false;
    }
    private function saveData()
    {
        $jsonData = json_encode($this->studentData, JSON_PRETTY_PRINT);
        file_put_contents('data\students.json', $jsonData, LOCK_EX);
    }
}
