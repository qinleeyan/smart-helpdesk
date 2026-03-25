import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { IconSearch, IconBook } from '@tabler/icons-react';

export default function Index({ auth, articles }) {
    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Knowledge Base</h1>}>
            <Head title="Knowledge Base" />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="mx-auto w-full max-w-2xl text-center mb-4">
                    <h2 className="text-2xl font-semibold mb-4">How can we help you?</h2>
                    <form method="GET" action={route('kb.index')} className="relative">
                        <IconSearch className="absolute left-3 top-2.5 size-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search articles..." className="flex h-9 w-full rounded-md border border-input bg-transparent pl-9 pr-3 py-1 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring" />
                    </form>
                </div>
                {auth.user.role === 'admin' && (
                    <div className="flex justify-end"><Link href={route('kb.admin.index')} className="inline-flex items-center rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm hover:bg-accent transition-colors">Manage Articles</Link></div>
                )}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {(!articles.data || articles.data.length === 0) ? (
                        <div className="col-span-full py-12 text-center text-muted-foreground"><IconBook className="mx-auto size-10 mb-2" /><p>No articles found.</p></div>
                    ) : articles.data.map(a => (
                        <Link key={a.id} href={route('kb.show', a.slug)} className="group rounded-xl border bg-card p-5 shadow-sm transition-colors hover:bg-accent/50">
                            <h3 className="font-semibold group-hover:text-primary transition-colors">{a.title}</h3>
                            <p className="mt-2 text-sm text-muted-foreground line-clamp-2">{a.content?.substring(0, 120)}...</p>
                            <div className="mt-3 flex justify-between text-xs text-muted-foreground">
                                <span>{a.category?.name || 'General'}</span>
                                <span>{a.views} views</span>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
