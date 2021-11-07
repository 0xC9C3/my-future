import React from 'react';

export default function ValidationErrors({errors}: any) {
    return Object.keys(errors).length > 0 ? (
        <div className="mb-4">
            <div className="font-medium text-red-600">
                Whoops! Something went wrong.
            </div>

            <ul className="mt-3 list-disc list-inside text-sm text-red-600">
                {Object.keys(errors).map(function (key, index) {
                    if (typeof errors[key] === 'object') {
                        return (
                            <li key={index}>
                                <ul className="mt-3 list-disc list-inside text-sm text-red-600">
                                    {Object.keys(errors[key]).map(function (
                                        k,
                                        i
                                    ) {
                                        return (
                                            <li key={i}>{errors[key][k]}</li>
                                        );
                                    })}
                                </ul>
                            </li>
                        );
                    }
                    return <li key={index}>{errors[key]}</li>;
                })}
            </ul>
        </div>
    ) : null;
}
