import React from 'react';
import {IconButton, useClipboard, useToast} from '@chakra-ui/react';
import {CopyIcon} from '@chakra-ui/icons';

// https://www.iconfinder.com/icons/473784/bag_briefcase_business_case_job_portfolio_suitcase_icon
// by Maxim Basinski
export default function CopyToClipboardButton({value}: {value: string}) {
    const {onCopy} = useClipboard(value);
    const toast = useToast();
    return (
        <IconButton
            icon={<CopyIcon />}
            aria-label="copy"
            onClick={() => {
                onCopy();
                toast({
                    title: 'Copied!',
                    status: 'success',
                    duration: 1000,
                    isClosable: true,
                });
            }}
        />
    );
}
