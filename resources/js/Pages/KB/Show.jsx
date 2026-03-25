import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { IconArrowLeft } from '@tabler/icons-react';

export default function Show({ article }) {
    return (
        <AuthenticatedLayout header={
            <div className="flex items-center gap-2">
                <Link href={route('kb.index')} className="text-muted-foreground hover:text-foreground"><IconArrowLeft className="size-4" /></Link>
                <h1 className="text-base font-medium">{article.title}</h1>
            </div>
        }>
            <Head title={article.title} />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="mx-auto w-full max-w-3xl rounded-xl border bg-card p-8 shadow-sm">
                    <h1 className="text-2xl font-semibold mb-2">{article.title}</h1>
                    <div className="flex gap-4 text-sm text-muted-foreground border-b pb-4 mb-6">
                        <span>{article.category?.name || 'General'}</span>
                        <span>{article.views} views</span>
                        <span>{new Date(article.updated_at).toLocaleDateString()}</span>
                    </div>
                    <div className="prose prose-sm max-w-none dark:prose-invert whitespace-pre-wrap">{article.content}</div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
