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
import {Application} from '@/Types/Application';

export default function DeleteApplicationModal({
    application,
    isOpen,
    onClose,
}: {
    application: Application;
} & ModalProps) {
    const toast = useToast();
    const {post, processing, errors} = useForm<{
        application: undefined | number;
    }>({
        application: application.id,
    });
    return (
        <Modal isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader>Delete application?</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <ValidationErrors errors={errors} />
                    <Box>
                        Do you want to delete the application?
                        <Alert className="my-2" status="error">
                            <AlertIcon />
                            <AlertTitle>
                                This will also delete all statistics associated
                                with this application.
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
                            post(route('dashboard.applications.delete'), {
                                onSuccess: () => {
                                    toast({
                                        title: 'Deleted',
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
