import React, {RefObject, useEffect, useState} from 'react';
import {Box, Button, Flex, SimpleGrid, Spacer} from '@chakra-ui/react';
import CodeMirror from '@uiw/react-codemirror';
import '@codemirror/autocomplete';
import {FullScreen, useFullScreenHandle} from 'react-full-screen';

export default function Editor(props: {
    preprocess?: (code: string) => string;
    extensions?: any[];
    value?: string;
    onChange: any;
}) {
    const renderRef: RefObject<HTMLIFrameElement> = React.createRef();
    // handle first render
    useEffect(() => {
        if (props.value) {
            handleValueChange(props.value);
        }
    }, [renderRef]);

    // https://github.com/uiwjs/react-codemirror/issues/199#issuecomment-942883244
    const [initialValue] = useState(props.value);

    const getBlobURL = (code: string, type: string) => {
        const blob = new Blob([code], {type});
        return URL.createObjectURL(blob);
    };
    const handleValueChange = (value: any) => {
        // @todo debounce
        let content = value;
        if (props.preprocess) {
            content = props.preprocess(content);
        }

        try {
            if (content !== props.value) {
                props.onChange(content);
            }
            const blobUrl = getBlobURL(content, 'text/html');
            if (renderRef.current) {
                renderRef.current.src = blobUrl;
            }
        } catch (e) {
            // notify user of error
            console.error(e);
        }
    };

    const handle = useFullScreenHandle();
    return (
        <Box className="h-full flex flex-col">
            <Flex className="pb-6">
                <Button onClick={handle.enter}>Fullscreen</Button>
                <Spacer />
            </Flex>
            <FullScreen className="h-full" handle={handle}>
                <SimpleGrid className="h-full" columns={2} spacing={10}>
                    <Box>
                        <CodeMirror
                            className="h-full"
                            value={initialValue}
                            height="100%"
                            extensions={props.extensions}
                            theme="dark"
                            onChange={value => handleValueChange(value)}
                        />
                    </Box>
                    <Box>
                        <iframe
                            className="bg-white h-full w-full"
                            ref={renderRef}
                        />
                    </Box>
                </SimpleGrid>
            </FullScreen>
        </Box>
    );
}
