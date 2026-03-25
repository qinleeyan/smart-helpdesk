import { Link, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Toaster, toast } from 'react-hot-toast';
import {
    IconDashboard,
    IconTicket,
    IconBook2,
    IconChartBar,
    IconLogout,
    IconMenu2,
    IconX,
    IconInnerShadowTop,
} from '@tabler/icons-react';

export default function AuthenticatedLayout({ header, children }) {
    const { auth, flash } = usePage().props;
    const user = auth.user;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    useEffect(() => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    }, [flash]);

    const navItems = [
        { title: 'Dashboard', href: route('dashboard'), icon: IconDashboard, active: route().current('dashboard') },
        { title: 'Tickets', href: route('tickets.index'), icon: IconTicket, active: route().current('tickets.*') },
        { title: 'Knowledge Base', href: route('kb.index'), icon: IconBook2, active: route().current('kb.*') },
    ];

    if (user.role === 'admin') {
        navItems.push({ title: 'Analytics', href: route('analytics.index'), icon: IconChartBar, active: route().current('analytics.*') });
    }

    return (
        <div className="flex h-screen overflow-hidden bg-background text-foreground font-sans antialiased">
            <Toaster toastOptions={{ style: { background: 'hsl(var(--card))', color: 'hsl(var(--foreground))', border: '1px solid hsl(var(--border))' } }} />
            {/* Mobile Overlay */}
            {sidebarOpen && (
                <div className="fixed inset-0 z-40 bg-black/80 lg:hidden" onClick={() => setSidebarOpen(false)} />
            )}

            {/* Sidebar */}
            <aside className={`fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-sidebar-border bg-sidebar transition-transform duration-300 lg:static lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
                {/* Logo */}
                <div className="flex h-14 items-center gap-2 border-b border-sidebar-border px-4">
                    <IconInnerShadowTop className="size-5 text-sidebar-primary" />
                    <span className="text-base font-semibold text-sidebar-foreground">SmartDesk</span>
                    <button className="ml-auto lg:hidden" onClick={() => setSidebarOpen(false)}>
                        <IconX className="size-5 text-sidebar-foreground" />
                    </button>
                </div>

                {/* Nav */}
                <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                    <Link
                        href={route('tickets.create')}
                        className="mb-4 flex w-full items-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors"
                    >
                        <svg className="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><path d="M8 12h8M12 8v8" /></svg>
                        Quick Create
                    </Link>

                    {navItems.map((item) => (
                        <Link
                            key={item.title}
                            href={item.href}
                            className={`flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors ${item.active
                                ? 'bg-sidebar-accent text-sidebar-accent-foreground'
                                : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground'
                                }`}
                        >
                            <item.icon className="size-4" />
                            {item.title}
                        </Link>
                    ))}
                </nav>

                {/* User */}
                <div className="border-t border-sidebar-border p-3">
                    <div className="flex items-center gap-3 rounded-md px-3 py-2">
                        <div className="flex size-8 items-center justify-center rounded-lg bg-muted text-xs font-medium">
                            {user.name.charAt(0).toUpperCase()}
                        </div>
                        <div className="flex-1 truncate">
                            <p className="truncate text-sm font-medium">{user.name}</p>
                            <p className="truncate text-xs text-muted-foreground">{user.email}</p>
                        </div>
                        <Link href={route('logout')} method="post" as="button" className="text-muted-foreground hover:text-destructive transition-colors">
                            <IconLogout className="size-4" />
                        </Link>
                    </div>
                </div>
            </aside>

            {/* Main Area */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Header */}
                <header className="flex h-14 shrink-0 items-center gap-2 border-b px-4 lg:px-6">
                    <button onClick={() => setSidebarOpen(true)} className="text-muted-foreground lg:hidden">
                        <IconMenu2 className="size-5" />
                    </button>
                    <div className="h-4 w-px bg-border mx-2 hidden lg:block" />
                    {header}
                </header>

                {/* Content */}
                <main className="flex-1 overflow-y-auto">
                    <div className="flex flex-1 flex-col">
                        <div className="@container/main flex flex-1 flex-col gap-2">
                            {children}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
}
