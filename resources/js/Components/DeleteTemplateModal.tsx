import React from 'react';
import {
    Alert,
    AlertIcon,
    AlertTitle,
    Box,
    Button,
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

export default function DeleteTemplateModal({
    template,
    isOpen,
    onClose,
}: {
    template: Template;
} & ModalProps) {
    const toast = useToast();
    const {post, processing, errors} = useForm<{
        template: undefined | number;
    }>({
        template: template.id,
    });
    return (
        <Modal isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader>Delete {template.name}</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <ValidationErrors errors={errors} />
                    <Box>
                        Do you want to delete {template.name}?
                        <Alert className="my-2" status="error">
                            <AlertIcon />
                            <AlertTitle>
                                This will also delete all applications
                                associated with this respondent.
                            </AlertTitle>
                        </Alert>
                    </Box>
                </ModalBody>

                <ModalFooter>
                    <Button variant="ghost" mr={3} onClick={onClose}>
                        Close
                    </Button>
                    <Button
                        colorScheme="red"
                        isLoading={processing}
                        onClick={() => {
                            post(route('dashboard.templates.delete'), {
                                onSuccess: () => {
                                    toast({
                                        title: 'Deleted ' + template.name,
                                        status: 'success',
                                        duration: 1000,
                                        isClosable: true,
                                    });
                                    onClose();
                                },
                            });
                        }}
                    >
                        Delete
                    </Button>
                </ModalFooter>
            </ModalContent>
        </Modal>
    );
}
