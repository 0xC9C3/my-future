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
import {Respondent} from '@/Types/Respondent';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {ModalProps} from '@/Types/ModalProps';

export default function DeleteRespondentModal({
    respondent,
    isOpen,
    onClose,
}: {
    respondent: Respondent;
} & ModalProps) {
    const toast = useToast();
    const {post, processing, errors} = useForm<{
        respondent: undefined | number;
    }>({
        respondent: respondent.id,
    });
    return (
        <Modal isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader>Delete {respondent.name}</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <ValidationErrors errors={errors} />
                    <Box>
                        Do you want to delete {respondent.name}?
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
                            post(route('dashboard.respondents.delete'), {
                                onSuccess: () => {
                                    toast({
                                        title: 'Deleted ' + respondent.name,
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
