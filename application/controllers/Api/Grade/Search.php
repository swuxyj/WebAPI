<?php

class Api_Grade_Search_Controller extends Api_Base_Controller
{

    protected function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'StudentId'=>'digits_between:15,15|required',
            'Term'=>'|numeric|max:3|min:0',
        ];
    }

    protected function messages(): array
    {
        // TODO: Implement messages() method.
        return [
            'StudentId.required'=>'学号不能为空',
            'StudentId.digits_between'=>'学号为15位',
            'Term.numeric'=>'学期必须为数字',
            'Term.max'=>'学期数必须为1,2,3',
            'Term.min'=>'学期数必须为1,2,3',
            ];
    }

    protected function process()
    {

        $Param = $this->getRequest()->getParams();
        $results = Service_Score_Model::Search_Score($Param['StudentId'], $Param['AcademicYear'], $Param['Term']);
        $respose = [];
        if ($results->isEmpty()) {
            $respose = [
                ['StudentId' => $Param['StudentId'],
                    'LessonName' => '结果不存在',
                    'Score' => '结果不存在',
                    'GradePoint' => '结果不存在',
                    'Credit' => '结果不存在',
                ]
            ];
        }
        else {
            foreach ($results as $result) {

                $result1=['StudentId'=>$result->student_id,
                        'Score'=>$result->score,
                        'LessonName'=>$result->lesson_name,
                        'AcademicYear'=>$result->academic_year,
                        'Term'=>$result->term,
                        'GradePoint'=>$result->grade_point,
                        'Credit'=>$result->credit,
                    ];

                array_push($respose, $result1);
            }
        }

        headers_sent() || header('Content-Type: application/json');

        $this->getResponse()->setBody(json_encode($respose, JSON_UNESCAPED_UNICODE));

    }

    /*function convertUnderline( $str , $ucfirst = true)
    {
        while(($pos = strpos($str , '_'))!==false)
            $str = substr($str , 0 , $pos).ucfirst(substr($str , $pos+1));

        return $ucfirst ? ucfirst($str) : $str;
    }*/


}