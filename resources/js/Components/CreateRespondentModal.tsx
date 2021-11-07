import React from 'react';
import {
    Button,
    Modal,
    ModalBody,
    ModalCloseButton,
    ModalContent,
    ModalFooter,
    ModalHeader,
    ModalOverlay,
} from '@chakra-ui/react';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {ModalProps} from '@/Types/ModalProps';
import CreateRespondentForm, {
    createRespondentBaseState,
    createRespondentSchema,
} from '@/Components/CreateRespondentForm';
import {Formik} from 'formik';

export default function CreateRespondentModal({isOpen, onClose}: ModalProps) {
    const {post, processing, errors, setData} = useForm(
        createRespondentBaseState
    );

    const submit = () => {
        post(route('dashboard.respondents.store'), {
            onSuccess: () => onClose(),
        });
    };
    return (
        <Modal isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <Formik
                validationSchema={createRespondentSchema}
                initialValues={createRespondentBaseState}
                onSubmit={submit}
            >
                {props => (
                    <ModalContent>
                        <ModalHeader>Create Respondent</ModalHeader>
                        <ModalCloseButton />
                        <ModalBody>
                            <ValidationErrors errors={errors} />
                            <CreateRespondentForm />
                        </ModalBody>

                        <ModalFooter>
                            <Button variant="ghost" mr={3} onClick={onClose}>
                                Close
                            </Button>
                            <Button
                                colorScheme="green"
                                isLoading={processing}
                                type="submit"
                                onClick={e => {
                                    e.preventDefault();
                                    setData(props.values);
                                    props.submitForm();
                                }}
                            >
                                Create
                            </Button>
                        </ModalFooter>
                    </ModalContent>
                )}
            </Formik>
        </Modal>
    );
}
