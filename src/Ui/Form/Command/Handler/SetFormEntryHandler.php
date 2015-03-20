<?php namespace Anomaly\Streams\Platform\Ui\Form\Command\Handler;

use Anomaly\Streams\Platform\Ui\Form\Command\SetFormEntry;
use Anomaly\Streams\Platform\Ui\Form\Contract\FormRepositoryInterface;

/**
 * Class SetFormEntryHandler
 *
 * @link    http://anomaly.is/streams-platform
 * @author  AnomalyLabs, Inc. <hello@anomaly.is>
 * @author  Ryan Thompson <ryan@anomaly.is>
 * @package Anomaly\Streams\Platform\Ui\Form\Command
 */
class SetFormEntryHandler
{

    /**
     * Set the form model object from the builder's model.
     *
     * @param SetFormEntry $command
     */
    public function handle(SetFormEntry $command)
    {
        $builder    = $command->getBuilder();
        $entry      = $builder->getEntry();
        $form       = $builder->getForm();
        $repository = $form->getRepository();

        /**
         * If the entry is null or an ID and the
         * model is an instance of FormModelInterface
         * then use the model to fetch the entry
         * or create a new one.
         */
        if (is_numeric($entry) || $entry === null) {
            if ($repository instanceof FormRepositoryInterface) {

                $form->setEntry($repository->findOrNew($entry));

                return;
            }
        }

        /**
         * If the entry is a plain 'ole
         * object  then just use it as is.
         */
        if (is_object($entry)) {

            $form->setEntry($entry);

            return;
        }

        /**
         * Whatever it is - just use it.
         */
        $form->setEntry($entry);
    }
}
