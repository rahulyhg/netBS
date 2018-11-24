<?php

namespace Ovesco\FacturationBundle\Form;

use Doctrine\DBAL\Types\TextType;
use NetBS\FichierBundle\Utils\Form\RemarquesUtils;
use Ovesco\FacturationBundle\Entity\Creance;
use Ovesco\FacturationBundle\Form\Type\DebiteurType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre de la créance'])
            ->add('montant', NumberType::class, ['label' => 'Montant'])
            ->add('rabais', NumberType::class, ['label' => 'Rabais (en francs)'])
            ->add('facture', EntityType::class, ['label' => 'facture', 'required' => false])
            ->add('debiteur', DebiteurType::class, ['label' => 'debiteur'])
        ;

        RemarquesUtils::addRemarquesField($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => Creance::class
        ]);
    }
}
