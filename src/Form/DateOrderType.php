<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DateOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('date', DateTimeType::class,[
            "minutes" => [
                '00',
                '15',
                '30', 
                '45'
            ],
            "hours" => range(8, 19),
            "years" => range(2022, 2024)
           
        ])
                ->add('Valider', SubmitType::class)
    ;
           
        ;
    }

   
}
