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

export default function ResendApplicationModal({
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
                <ModalHeader>Resend application?</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <ValidationErrors errors={errors} />
                    <Box>
                        Do you want to resend the application?
                        <Alert className="my-2" status="error">
                            <AlertIcon />
                            <AlertTitle>@TODO add preview</AlertTitle>
                        </Alert>
                    </Box>
                </ModalBody>

                <ModalFooter>
                    <Button variant="ghost" mr={3} onClick={onClose}>
                        Close
                    </Button>
                    <Button
                        colorScheme="green"
                        isLoading={processing}
                        onClick={() => {
                            post(route('dashboard.applications.resend'), {
                                onSuccess: () => {
                                    toast({
                                        title: 'Resent',
                                        status: 'success',
                                        duration: 1000,
                                        isClosable: true,
                                    });
                                    onClose();
                                },
                            });
                        }}
                    >
                        Resend
                    </Button>
                </ModalFooter>
            </ModalContent>
        </Modal>
    );
}
