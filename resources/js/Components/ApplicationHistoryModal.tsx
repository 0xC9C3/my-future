import React, {useEffect, useState} from 'react';
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
    Table,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
    useToast,
} from '@chakra-ui/react';
import {useForm} from '@inertiajs/inertia-react';
import ValidationErrors from '@/Components/ValidationErrors';
import {ModalProps} from '@/Types/ModalProps';
import {Template} from '@/Types/Template';
import {Application} from '@/Types/Application';
import StatisticsList from '@/Components/StatisticsList';
import {Statistic} from '@/Types/Statistic';
import axios from 'axios';

export default function ApplicationHistoryModal({
    application,
    isOpen,
    onClose,
}: {
    application: Application;
} & ModalProps) {
    const [statistics, setStatistics] = useState<Statistic[]>([]);

    useEffect(() => {
        axios
            .get(
                route('dashboard.applications.statistics', {
                    applicationId: application.id,
                })
            )
            .then(resp => setStatistics(resp.data));
    }, []);

    return (
        <Modal size="4xl" isOpen={isOpen} onClose={onClose} isCentered>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader>Application History</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <Box>
                        <StatisticsList statistics={statistics} />
                    </Box>
                </ModalBody>

                <ModalFooter>
                    <Button variant="ghost" mr={3} onClick={onClose}>
                        Close
                    </Button>
                </ModalFooter>
            </ModalContent>
        </Modal>
    );
}
