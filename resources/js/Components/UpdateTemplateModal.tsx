import React from 'react';
import {
    Button,
    FormControl,
    FormLabel,
    Input,
    Modal,
    ModalBody,
    ModalCloseButton,
    ModalContent,
    ModalFooter,
    ModalHeader,
    ModalOverlay,
    useToast,
} from '@chakra-ui/react';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {ModalProps} from '@/Types/ModalProps';
import {Template} from '@/Types/Template';

export default function UpdateTemplateModal({
    template,
    isOpen,
    onClose,
}: {
    template: Template;
} & ModalProps) {
    const toast = useToast();
    const {post, processing, setData, errors} = useForm<{
        template: undefined | number;
        file: any;
    }>({
        template: template.id,
        file: null,
    });
    const submit = (e: any) => {
        e.preventDefault();

        post(route('dashboard.templates.update'), {
            forceFormData: true,
            onSuccess: () => {
                toast({
                    title: 'Updated',
                    status: 'success',
                    duration: 1000,
                    isClosable: true,
                });
                onClose();
            },
        });
    };
    return (
        <Modal isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <ModalContent>
                <form onSubmit={submit}>
                    <ModalHeader>Update {template.name}</ModalHeader>
                    <ModalCloseButton />
                    <ModalBody>
                        <ValidationErrors errors={errors} />
                        <FormControl className="py-3">
                            <FormLabel>File</FormLabel>
                            <Input
                                onChange={event => {
                                    setData(
                                        'file',
                                        event.target.files &&
                                            event.target.files.length
                                            ? event.target.files[0]
                                            : null
                                    );
                                }}
                                name="template"
                                accept=".zip"
                                type="file"
                            />
                        </FormControl>
                    </ModalBody>
                    <ModalFooter>
                        <Button variant="ghost" mr={3} onClick={onClose}>
                            Close
                        </Button>
                        <Button isLoading={processing} type="submit">
                            Update
                        </Button>
                    </ModalFooter>
                </form>
            </ModalContent>
        </Modal>
    );
}
