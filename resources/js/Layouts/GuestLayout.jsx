import { Link } from '@inertiajs/react';
import { IconInnerShadowTop } from '@tabler/icons-react';

export default function Guest({ children }) {
    return (
        <div className="flex min-h-screen flex-col items-center justify-center bg-background text-foreground font-sans antialiased">
            <div className="mb-6 flex items-center gap-2">
                <IconInnerShadowTop className="size-6" />
                <span className="text-xl font-semibold">SmartDesk</span>
            </div>
            <div className="w-full max-w-sm rounded-xl border bg-card p-8 shadow-lg">
                {children}
            </div>
        </div>
    );
}
